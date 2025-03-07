<?php 
class OsGoogleCalendarHelper {
	public static function is_enabled(){
		return OsSettingsHelper::is_on('enable_google_calendar');
	}

	public static function is_google_meet_enabled_for_service($service_id){
		return (OsMetaHelper::get_service_meta_by_key('enable_google_meet', $service_id) == 'on');
	}

	public static function get_google_meet_conference_url_for_booking_id($booking_id): string{
		$meet_url = '';
		$booking = new OsBookingModel($booking_id);
		$meet_id = $booking->get_meta_by_key('google_calendar_event_meet_id');
		if(!empty($meet_id)) $meet_url = 'https://meet.google.com/'.$meet_id;
		return $meet_url;
	}

  public static function os_get_start_of_google_event($google_event){
  	if(!empty($google_event->start->dateTime)){
  		$date_string = $google_event->start->dateTime;
  		$date_format = \DateTime::RFC3339;
			$timezone = new DateTimeZone($google_event->start->timeZone);
  	}else{
			// Full day event
  		$date_string = $google_event->start->date.' 00:00:00';
  		$date_format = 'Y-m-d H:i:s';
			$timezone = false;
  	}
		return OsWpDateTime::os_createFromFormat($date_format, $date_string, $timezone);
  }

  public static function os_get_end_of_google_event($google_event){
  	if(!empty($google_event->end->dateTime)){
  		$date_string = $google_event->end->dateTime;
  		$date_format = \DateTime::RFC3339;
			return OsWpDateTime::os_createFromFormat($date_format, $date_string);
  	}else{
			// Full day event
			// !important,  in full day events of Google Calendar - start day is inclusive and the end day is exclusive https://stackoverflow.com/questions/34992747/google-calendar-json-api-full-day-events-always-one-day-longer
  		$date_string = $google_event->end->date.' 23:59:59';
      $date_format = 'Y-m-d H:i:s';
			$temp_date = OsWpDateTime::os_createFromFormat($date_format, $date_string);
			if($temp_date){
				// move back 1 day to accomodate Google rule that end date is 1 day ahead of actual end date of a full day event
				$temp_date->modify('-1 day');
			}else{
				OsDebugHelper::log('Error syncing Google Event'. $google_event->end->date, 'google_calendar_error');
			}
			return $temp_date;
  	}
  }

	public static function remove_calendar_for_push($agent_id){
		OsMetaHelper::delete_agent_meta_by_key('google_cal_selected_calendar_id_for_push', $agent_id);
	}

	public static function remove_calendar_for_pull($calendar_id_to_remove, $agent_id){
		$current_calendar_ids = self::get_selected_calendar_ids_for_pull($agent_id);
		$current_calendar_ids_arr = $current_calendar_ids ? explode(',', $current_calendar_ids) : [];
		$remaining_calendar_ids = [];
		foreach($current_calendar_ids_arr as $current_calendar_id){
			if($current_calendar_id != $calendar_id_to_remove) $remaining_calendar_ids[] = $current_calendar_id;
		}
		if(empty($remaining_calendar_ids)){
			return OsMetaHelper::delete_agent_meta_by_key('google_cal_selected_calendar_ids_for_pull', $agent_id);
		}else{
			return self::set_selected_calendar_ids_for_pull(implode(',', $remaining_calendar_ids), $agent_id);
		}
	}

	public static function add_calendar_for_pull($calendar_id, $agent_id){
		$current_calendar_ids = self::get_selected_calendar_ids_for_pull($agent_id);
		$current_calendar_ids_arr = $current_calendar_ids ? explode(',', $current_calendar_ids) : [];
		if(!in_array($calendar_id, $current_calendar_ids_arr)) {
			$current_calendar_ids_arr[] = $calendar_id;
			return self::set_selected_calendar_ids_for_pull(implode(',', $current_calendar_ids_arr), $agent_id);
		}
		return true;
	}

  public static function get_event_title_template(){
    return OsSettingsHelper::get_settings_value('google_calendar_event_summary_template', '{{service_name}}');
  }

  public static function get_event_description_template(){
    return OsSettingsHelper::get_settings_value('google_calendar_event_description_template', "Customer Name: <strong>{{customer_full_name}}</strong><br/>Phone: <strong>{{customer_phone}}</strong>");
  }

	public static function is_full_day_event($start_time, $end_time){
		return ($start_time == 0 && $end_time == 1439);
	}

  public static function get_events_for_date($target_date, $agent_id = false){

    $events_model = new OsGoogleCalendarEventModel();

    if(!OsTimeHelper::is_valid_date($target_date)) return [];

    $target_date = OsWpDateTime::CreateFromFormat("Y-m-d", $target_date);
    if(!$target_date) return [];

    $weekday = OsTimeHelper::get_db_weekday_by_number($target_date->format('N'));
    $events_model->escape_by_ref($weekday);
    $weekday_relative = ceil($target_date->format('j') / 7).$weekday;

    if(($target_date->format('t') - $target_date->format('j')) < 7){
      $last_weekday_query = " OR (`weekday` = '-1{$weekday}') ";
    }else{
      $last_weekday_query = '';
    }

    // clean 

    $formatted_date = $target_date->format('Y-m-d');
    $events_model->escape_by_ref($formatted_date);

    $query = "SELECT events.start_time, events.end_time, events.id, events.summary, agent_id FROM ".LATEPOINT_TABLE_GCAL_EVENTS." as events
              LEFT JOIN ".LATEPOINT_TABLE_GCAL_RECURRENCES." as recs ON events.id = recs.lp_event_id
              WHERE
                (`start_date` = '{$formatted_date}'
                  OR (`start_date` <= '{$formatted_date}' && `end_date` >= '{$formatted_date}')
                  OR (`frequency` = 'daily' 
                    AND (DATEDIFF('{$formatted_date}', `start_date`) % `interval`) = 0) 
                    AND (`count` IS NULL OR FLOOR(DATEDIFF('{$formatted_date}', `start_date`) / `interval`) < `count`)
                  OR (`frequency` = 'weekly' 
                    AND `weekday` = '{$weekday}' 
                    AND ((FLOOR(DATEDIFF('{$formatted_date}', `start_date`)/7) % `interval`) = 0) 
                    AND (`count` IS NULL OR FLOOR(FLOOR(DATEDIFF('{$formatted_date}', `start_date`)/7) / `interval`) < `count`)) 
                  OR (`frequency` = 'monthly'
                    AND ((DAYOFMONTH(`start_date`) = DAYOFMONTH('{$formatted_date}') OR (`weekday` = '{$weekday_relative}') {$last_weekday_query}) 
                    AND (PERIOD_DIFF(EXTRACT(YEAR_MONTH FROM '{$formatted_date}'), EXTRACT(YEAR_MONTH FROM `start_date`)) % `interval`) = 0) 
                    AND (`count` IS NULL OR FLOOR(PERIOD_DIFF(EXTRACT(YEAR_MONTH FROM '{$formatted_date}'), EXTRACT(YEAR_MONTH FROM `start_date`)) / `interval`) < `count`))    
                  OR (`frequency` = 'yearly' 
                    AND date_format(`start_date`, '%m%d') = date_format('{$formatted_date}', '%m%d') 
                    AND ((EXTRACT(YEAR FROM '{$formatted_date}') - EXTRACT(YEAR FROM `start_date`)) % `interval` = 0) 
                    AND (`count` IS NULL OR FLOOR(EXTRACT(YEAR FROM '{$formatted_date}') - EXTRACT(YEAR FROM `start_date`) / `interval`) < `count`))
                )AND (`start_date` <= '{$formatted_date}' AND (`until` > '{$formatted_date}' OR `until` IS NULL))";
    if($agent_id) {
			if(is_array($agent_id)){
				$agent_ids = implode(',', $agent_id);
				$query.= " AND agent_id IN ({$agent_ids})";
			}else{
				$query.= " AND agent_id = {$agent_id}";
			}
    }
    $events = $events_model->get_query_results( $query );
    return $events;
  }

  public static function stop_watch($agent_id, $calendar_id){
    $agent = new OsAgentModel($agent_id);
    if(!$agent->id) return false;

    $agent_watch_channels = $agent->get_meta_by_key('google_cal_agent_watch_channels');
		$agent_watch_channels_arr = json_decode($agent_watch_channels, true);
		$agent_watch_channel = false;
		if($agent_watch_channels_arr){
			foreach($agent_watch_channels_arr as $channel){
				if($channel['calendar_id'] == $calendar_id) {
					$agent_watch_channel = $channel;
					break;
				}
			}
		}
    if(!$agent_watch_channel) return false;


    $client = OsGoogleCalendarHelper::get_authorized_client_for_agent($agent->id);
    if(!$client) return false;
    $g_service = new \LatePoint\GoogleCalendarAddon\Google\Service\Calendar($client);

    $g_channel = new \LatePoint\GoogleCalendarAddon\Google\Service\Calendar\Channel();
    $g_channel->setId($agent_watch_channel['id']);
    $g_channel->setResourceId($agent_watch_channel['resourceId']);
    try{
      $response = $g_service->channels->stop($g_channel);
    }catch(Exception $e){
      OsDebugHelper::log('Error stopping Google Calendar channel watch. '.$e->getMessage(), 'google_calendar_error');
    }
		$updated_agent_watch_channels_arr = [];
		foreach($agent_watch_channels_arr as $channel){
			if($channel['calendar_id'] != $calendar_id) $updated_agent_watch_channels_arr[] = $channel;
		}
		if($updated_agent_watch_channels_arr){
			$agent->save_meta_by_key('google_cal_agent_watch_channels', json_encode($updated_agent_watch_channels_arr));
		}else{
	    $agent->delete_meta_by_key('google_cal_agent_watch_channels');
		}
  }

  public static function refresh_watch($agent_id, $calendar_id){
    self::stop_watch($agent_id, $calendar_id);
    self::start_watch($agent_id, $calendar_id);
  }

  public static function translate_weekdays($weekday){
    $weekday = str_replace(',', ', ', $weekday);
    return str_replace(['MO', 'TU', 'WE', 'TH', 'FR', 'SA', 'SU'], [__('Mon', 'latepoint-google-calendar'), __('Tue', 'latepoint-google-calendar'), __('Wed', 'latepoint-google-calendar'), __('Thu', 'latepoint-google-calendar'), __('Fri', 'latepoint-google-calendar'), __('Sat', 'latepoint-google-calendar'), __('Sun', 'latepoint-google-calendar')], $weekday);
  }

  public static function get_gcal_event_recurrences($gcal_event, $split_weekdays = true){
    $rrule = false;
    $gcal_recurrence = new OsGcalEventRecurrenceModel();
    foreach($gcal_event->getRecurrence() as $rec){
      if(!strstr($rec, 'RRULE:')) continue;
      $rrule = str_replace('RRULE:', '', $rec);
    }
    if(!$rrule) return false;
    $rrules = false;
    parse_str((str_replace(';', '&', $rrule)), $rrules);
    if(!$rrules) return false;
    //$rrules = explode(';', $rrule);
    $gcal_recurrence->start_date = $gcal_event->start_date;
    if(isset($rrules['FREQ'])) $gcal_recurrence->frequency = $rrules['FREQ'];
    if(isset($rrules['UNTIL'])){
      $rrules['UNTIL'] = strtok($rrules['UNTIL'], 'T');
      $gcal_recurrence->until = $rrules['UNTIL'];
    }
    if(isset($rrules['INTERVAL'])){
      $gcal_recurrence->interval = $rrules['INTERVAL'];
    }else{
      $gcal_recurrence->interval = 1;
    }
    if(isset($rrules['COUNT'])) $gcal_recurrence->count = $rrules['COUNT'];
    
    if(isset($rrules['BYDAY'])){
      $gcal_recurrences = [];
      $weekdays = explode(',', $rrules['BYDAY']);
      if($split_weekdays && (count($weekdays) > 1)){
        foreach($weekdays as $byday){
          $gcal_recurrence->weekday = $byday;
          $gcal_recurrences[] = clone $gcal_recurrence;
        }
      }else{
        $gcal_recurrence->weekday = $rrules['BYDAY'];
      }
    }
    if(empty($gcal_recurrences)){
      $gcal_recurrences[] = $gcal_recurrence;
    }
    return $gcal_recurrences;
  }

	public static function is_calendar_being_watched($calendar_id, $agent_id, $agent_watch_channels_arr = []){
		// if not passed, query the db
		if(empty($agent_watch_channels_arr)){
			$agent_watch_channels = OsMetaHelper::get_agent_meta_by_key('google_cal_agent_watch_channels', $agent_id, '');
			$agent_watch_channels_arr = json_decode($agent_watch_channels, true);
		}
		if($agent_watch_channels_arr){
			foreach($agent_watch_channels_arr as $channel){
				if($channel['calendar_id'] == $calendar_id) return $channel;
			}
		}
		return false;
	}

  public static function start_watch($agent_id, $calendar_id){
    $agent = new OsAgentModel($agent_id);
    if(!$agent->id) return false;
    $agent_watch_channels = $agent->get_meta_by_key('google_cal_agent_watch_channels');
		$agent_watch_channels_arr = json_decode($agent_watch_channels, true);
		$agent_watch_channel = false;
		if($agent_watch_channels_arr){
			foreach($agent_watch_channels_arr as $channel){
				if($channel['calendar_id'] == $calendar_id) {
					$agent_watch_channel = true;
					break;
				}
			}
		}

    // no watch channel exist yet for this agent
    if(!$agent_watch_channel){
      $client = OsGoogleCalendarHelper::get_authorized_client_for_agent($agent->id);
      if(!$client) return false;
      $g_service = new \LatePoint\GoogleCalendarAddon\Google\Service\Calendar($client);
      $g_channel = new \LatePoint\GoogleCalendarAddon\Google\Service\Calendar\Channel();
      $channel_id = 'gcal_channel_'.uniqid('');
      $g_channel->setId($channel_id);
      $g_channel->setType('web_hook');
      // in reality it expires earlier (1 month is max limit for google api as of now)
      $g_channel->setExpiration(strtotime('+2 months') * 1000);
      $g_channel->setAddress(OsRouterHelper::build_admin_post_link(['google_calendar', 'event_watch_updated'], ['agent_id' => $agent_id, 'calendar_id' => $calendar_id]));
      

      try{
        $response = $g_service->events->watch($calendar_id, $g_channel);
        if(is_array($response) && isset($response['error'])){
          OsDebugHelper::log('Google Calendar, Error starting watch channel', 'google_calendar_error', $response);
        }elseif(is_a($response, '\LatePoint\GoogleCalendarAddon\Google\Service\Calendar\Channel')){
          $agent_watch_channels_arr[] = [ 'expiration' => $response->expiration,
                                          'id' => $response->id,
                                          'resourceId' => $response->resourceId,
                                          'resourceUri' => $response->resourceUri,
                                          'calendar_id' => $calendar_id,
                                          'next_sync_token' => ''
                                        ];
          $agent->save_meta_by_key('google_cal_agent_watch_channels', json_encode($agent_watch_channels_arr));
        }
      }catch(Exception $e){
        if($e->getCode() == 401){
          throw new Exception('Auto-sync failed. You need to verify your domain in Google Developer tools.');
        }else{
          OsDebugHelper::log('Error starting Google Calendar watch channel. '.$e->getMessage(), 'google_calendar_error');
        }
      }
    }

  }


  public static function get_selected_calendar_id_for_push(int $agent_id): string{
    return OsMetaHelper::get_agent_meta_by_key('google_cal_selected_calendar_id_for_push', $agent_id);
  }

  public static function get_selected_calendar_ids_for_pull(int $agent_id): string{
    return OsMetaHelper::get_agent_meta_by_key('google_cal_selected_calendar_ids_for_pull', $agent_id);
  }

  public static function set_selected_calendar_id_for_push($calendar_id, $agent_id){
    return OsMetaHelper::save_agent_meta_by_key('google_cal_selected_calendar_id_for_push', $calendar_id, $agent_id);
  }

  public static function set_selected_calendar_ids_for_pull($calendar_ids, $agent_id){
    return OsMetaHelper::save_agent_meta_by_key('google_cal_selected_calendar_ids_for_pull', $calendar_ids, $agent_id);
  }

  public static function get_record_by_google_event_id($google_event_id){
    $google_event = new OsGoogleCalendarEventModel();
    return $google_event->where(['google_event_id' => $google_event_id])->set_limit(1)->get_results_as_models();
  }

  public static function is_agent_connected_to_gcal($agent_id, $verify_token = false){
    return self::get_access_token_for_agent($agent_id);
  }

  public static function remove_booking_from_gcal($booking_id, $custom_agent_id = false){
    $booking = new OsBookingModel();
    if(!$booking->load_by_id($booking_id)) return true;
    $agent_id = ($custom_agent_id != false) ? $custom_agent_id : $booking->agent_id;
    $calendar_id = self::get_selected_calendar_id_for_push($agent_id);
    $google_calendar_event_id = $booking->get_meta_by_key('google_calendar_event_id', false);
    if(!$google_calendar_event_id) return true;
    $g_client = self::get_authorized_client_for_agent($agent_id);

    $g_service = new \LatePoint\GoogleCalendarAddon\Google\Service\Calendar($g_client);
    try{
      if($g_service->events->delete($calendar_id, $google_calendar_event_id)){
        $booking->delete_meta_by_key('google_calendar_event_id');
        $booking->delete_meta_by_key('google_calendar_event_meet_id');
      }
    }catch(Exception $e){
      if($e->getCode() == 410 || $e->getCode() == 404){
        $booking->delete_meta_by_key('google_calendar_event_id');
        $booking->delete_meta_by_key('google_calendar_event_meet_id');
      }
      OsDebugHelper::log('Error removing booking from Google Calendar. '.$e->getMessage(), 'google_calendar_error');
    }
    return true;
  }

  public static function unsync_google_event_from_db($google_event_id){
    if(!$google_event_id) return true;
    $event_in_db = new OsGoogleCalendarEventModel();
    $events_to_unsync = $event_in_db->where(['google_event_id' => $google_event_id])->get_results_as_models();
    if($events_to_unsync){
      foreach($events_to_unsync as $event_model){
        $event_model->delete();
      }
    }
    return true;
  }

	public static function get_calendar_name_by_id($calendar_id, $agent_id){
    $calendars = self::get_list_of_calendars($agent_id);
    if(!empty($calendars)) {
	    foreach ($calendars as $calendar) {
				if($calendar['id'] == $calendar_id) return $calendar['title'];
	    }
    }
		return false;
	}

  public static function get_google_event_from_gcal_by_id($google_event_id, $google_calendar_id, $agent_id){
    $g_client = self::get_authorized_client_for_agent($agent_id);
    $g_service = new \LatePoint\GoogleCalendarAddon\Google\Service\Calendar($g_client);
    $event_in_gcal = $g_service->events->get($google_calendar_id, $google_event_id);
    return $event_in_gcal;
  }

  public static function get_list_of_calendars_for_select($agent_id, $include_empty = false){
    $calendars = self::get_list_of_calendars($agent_id);
    $calendars_for_select = [];
		if($include_empty) $calendars_for_select[] = ['value' => '', 'label' => __('Do not sync', 'latepoint-google-calendar')];
    if(!empty($calendars)){
      foreach($calendars as $calendar){
        $calendars_for_select[] = ['value' => $calendar['id'], 'label' => $calendar['title']];
      }
    }
    return $calendars_for_select;
  }

  public static function get_list_of_calendars($agent_id){
    $calendars = [];
    try{
      $g_service = OsGoogleCalendarHelper::get_g_service_for_agent($agent_id);
      if(!$g_service) return [];
      $calendarList = $g_service->calendarList->listCalendarList();
      while(true) {
        foreach ($calendarList->getItems() as $calendarListEntry) {
          $calendars[] = ['id' => $calendarListEntry->getId(), 'title' => $calendarListEntry->getSummary(), 'description' => $calendarListEntry->getDescription()];
        }
        $pageToken = $calendarList->getNextPageToken();
        if ($pageToken) {
          $optParams = array('pageToken' => $pageToken);
          $calendarList = $g_service->calendarList->listCalendarList($optParams);
        } else {
          break;
        }
      }
    }catch(Exception $e){
      OsDebugHelper::log('Error getting list of google calendars: '.$e->getMessage(), 'google_calendar_error');
    }
		usort($calendars, function ($item1, $item2) {
	    return $item1['title'] <=> $item2['title'];
		});
    return $calendars;
  }

  // if booking was changed on google calendar - update it on our DB
  public static function update_booking_from_gcal_event($gcal_event, $booking_id){
    $booking = new OsBookingModel($booking_id);
    if(!$booking->id) return false;

    $start_date_obj = self::os_get_start_of_google_event($gcal_event);
    $end_date_obj = self::os_get_end_of_google_event($gcal_event);


    if(!$start_date_obj || !$end_date_obj){
      OsDebugHelper::log('Google Event info is invalid', 'google_calendar_error');
      return;
    }

		$old_booking = clone $booking;

    $booking->start_date = $start_date_obj->format('Y-m-d');
    $booking->start_time = OsTimeHelper::convert_time_to_minutes($start_date_obj->format('H:i'), false);
    $booking->end_date = $end_date_obj->format('Y-m-d');
    $booking->end_time = OsTimeHelper::convert_time_to_minutes($end_date_obj->format('H:i'), false);
		$booking->set_utc_datetimes();
    if($booking->save()){
      do_action('latepoint_booking_updated', $booking, $old_booking);
    }else{
			OsDebugHelper::log('Error updating booking from Google Calendar Event', 'gcal_sync_error', $booking->get_error_messages());
    }
  }



  // event object can be passed as well as event id
  public static function create_or_update_google_event_in_db($google_event_id, $google_calendar_id, $agent_id){
    if(!$google_event_id || !$agent_id || !$google_calendar_id) return true;
    // load info from google about event
    if(isset($google_event_id->id)){
      $event_in_gcal = $google_event_id;
      $google_event_id = $event_in_gcal->id;
    }else{
      $event_in_gcal = self::get_google_event_from_gcal_by_id($google_event_id, $google_calendar_id, $agent_id);
    }

    $start_date_obj = self::os_get_start_of_google_event($event_in_gcal);
    $end_date_obj = self::os_get_end_of_google_event($event_in_gcal);

		$start_date_obj_utc = OsWpDateTime::datetime_in_utc($start_date_obj);
		$end_date_obj_utc = OsWpDateTime::datetime_in_utc($end_date_obj);

    if(!$start_date_obj || !$end_date_obj){
      OsDebugHelper::log('Google Calendar Event info is invalid', 'google_calendar_error');
      return;
    }


    // save event info to our database
    $google_calendar_event_in_db = new OsGoogleCalendarEventModel();
    $event_in_db = $google_calendar_event_in_db->where(['google_event_id' => $google_event_id, 'google_calendar_id' => $google_calendar_id])->set_limit(1)->get_results_as_models();

    if(!$event_in_db){
      // create new
      $event_in_db = new OsGoogleCalendarEventModel();
      $event_in_db->google_calendar_id = $google_calendar_id;
      $event_in_db->google_event_id = $google_event_id;
    }

    $event_in_db->agent_id = $agent_id;
    $event_in_db->summary = $event_in_gcal->getSummary();
    $event_in_db->html_link = $event_in_gcal->getHtmlLink();
    $event_in_db->start_date = $start_date_obj->format('Y-m-d');
    $event_in_db->start_time = OsTimeHelper::convert_time_to_minutes($start_date_obj->format('H:i'), false);
		$event_in_db->start_datetime_utc = $start_date_obj_utc->format('Y-m-d H:i:s');
		$event_in_db->end_datetime_utc = $end_date_obj_utc->format('Y-m-d H:i:s');
    $event_in_db->end_date = $end_date_obj->format('Y-m-d');
    $event_in_db->end_time = OsTimeHelper::convert_time_to_minutes($end_date_obj->format('H:i'), false);

    $result = $event_in_db->save();
    if($result && $event_in_gcal->getRecurrence()){
      $recurrences = self::get_gcal_event_recurrences($event_in_gcal);
      $event_in_db->update_recurrences($recurrences);
    }

    return $result;
  }

  public static function delete_booking_in_gcal($booking_id){

    $booking = new OsBookingModel();
    if(!$booking->load_by_id($booking_id)) return false;
    $calendar_id = self::get_selected_calendar_id_for_push($booking->agent_id);
    $g_client = self::get_authorized_client_for_agent($booking->agent_id);


    if($g_client){
      $g_service = new \LatePoint\GoogleCalendarAddon\Google\Service\Calendar($g_client);
      $google_calendar_event_id = $booking->get_meta_by_key('google_calendar_event_id', false);
      if($google_calendar_event_id){
        $g_service->events->delete($calendar_id, $google_calendar_event_id);
      }
    }
  }


	/**
	 *
	 * Check if a booking status should be synced to Google calendar
	 *
	 * @param string $booking_status
	 * @return bool
	 */
	public static function is_booking_status_syncable(string $booking_status): bool{
		return in_array($booking_status, self::get_enabled_booking_statuses());
	}

  public static function get_enabled_booking_statuses() {
		$timeslot_blocking_statuses = OsBookingHelper::get_timeslot_blocking_statuses();
	  /**
	   * Get the list of booking statuses that are enabled for synchronization with Google Calendar
	   *
	   * @since 1.3.0
	   * @hook latepoint_google_calendar_enabled_booking_statuses
	   *
	   * @param {array} $statuses Array of enabled booking statuses
	   *
	   * @returns {array} Filtered array of enabled booking statuses
	   */
		return apply_filters('latepoint_google_calendar_enabled_booking_statuses', $timeslot_blocking_statuses);
  }

  public static function create_or_update_booking_in_gcal($booking_id){

    $booking = new OsBookingModel();
    if(!$booking->load_by_id($booking_id)) return false;

    $google_calendar_event_id = $booking->get_meta_by_key('google_calendar_event_id', false);

    $calendar_id = self::get_selected_calendar_id_for_push($booking->agent_id);
		if(!$calendar_id) return false;

    $g_client = self::get_authorized_client_for_agent($booking->agent_id);
		if(!$g_client) return false;
    $g_service = new \LatePoint\GoogleCalendarAddon\Google\Service\Calendar($g_client);

    try{
      if(self::is_booking_status_syncable($booking->status)){
        // Status enabled, add or update event in google calendar
        //
        $attendies = [['email' => $booking->customer->email, 'displayName' => $booking->customer->full_name]];
        $description = OsGoogleCalendarHelper::get_event_description_template();
        $description = OsReplacerHelper::replace_all_vars($description, array('customer' => $booking->customer, 'agent' => $booking->agent, 'booking' => $booking));

        $summary = OsGoogleCalendarHelper::get_event_title_template();
        $summary = OsReplacerHelper::replace_all_vars($summary, array('customer' => $booking->customer, 'agent' => $booking->agent, 'booking' => $booking));

				$event_data = [
          'summary' => $summary,
          'location' => $booking->location->full_address,
          'attendies' => $attendies,
          'description' => $description,
          'start' => [
            'dateTime' => $booking->format_start_date_and_time_for_google(),
            'timeZone' => OsTimeHelper::get_wp_timezone_name(),
          ],
          'end' => [
            'dateTime' => $booking->format_end_date_and_time_for_google(),
            'timeZone' => OsTimeHelper::get_wp_timezone_name(),
          ]
        ];
        // add google meet
	      // TODO finish google meet integration
				if(OsMeetingSystemsHelper::is_external_meeting_system_enabled('google_meet') && OsGoogleCalendarHelper::is_google_meet_enabled_for_service($booking->service_id)){
					$add_google_meet = true;
					$event_data['conferenceData'] = [
						'createRequest' => [
							'conferenceSolutionKey' => [
								'type' => 'hangoutsMeet'
							],
							'requestId' => OsUtilHelper::random_text('alnum', 10)
						]
					];
				}else{
					$add_google_meet = false;
				}
        $event = new \LatePoint\GoogleCalendarAddon\Google\Service\Calendar\Event($event_data);
        if($google_calendar_event_id){
          // Existing google event
          $event = $g_service->events->update($calendar_id, $google_calendar_event_id, $event);
        }else{
          // new event in google cal
          $event = $g_service->events->insert($calendar_id, $event, ['conferenceDataVersion' => 1]);
          $booking->save_meta_by_key('google_calendar_event_id', $event->getId());
          if($add_google_meet) $booking->save_meta_by_key('google_calendar_event_meet_id', $event->conferenceData->conferenceId);
        }
      }else{
        // Status not enabled, remove event from calendar if exists and clean the booking meta
        if($google_calendar_event_id){
          $g_service->events->delete($calendar_id, $google_calendar_event_id);
          $booking->delete_meta_by_key('google_calendar_event_id');
          $booking->delete_meta_by_key('google_calendar_event_meet_id');
        }
      }
    }catch(Exception $e){
      if($e->getCode() == 410 || $e->getCode() == 404){
        $booking->delete_meta_by_key('google_calendar_event_id');
      }
      OsDebugHelper::log('Can not update booking ['.$booking_id.'] in Google Calendar: '.$e->getMessage(), 'google_calendar_error');
      return false;
    }
    return true;
  }


  public static function get_google_user_email_by_access_token($access_token)
  {
		if(!isset($access_token['access_token'])) {
			OsDebugHelper::log('Missing/Invalid Google Calendar access token', 'google_calendar_error');
			return false;
		}
	  $url = "https://www.googleapis.com/oauth2/v3/tokeninfo?access_token={$access_token['access_token']}";
	  $ch = curl_init();
	  $curlConfig = array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
	  );
	  curl_setopt_array($ch, $curlConfig);
	  $result = curl_exec($ch);
	  curl_close($ch);
	  $userinfo = json_decode($result, true);
		if(isset($userinfo['email'])){
			return $userinfo['email'];
		}else{
			OsDebugHelper::log('Can not get user email from google by access token', 'google_calendar_error', $userinfo['error_description']);
			return false;
		}
  }

	public static function get_client(){
    $g_client = new \LatePoint\GoogleCalendarAddon\Google\Client();
    $g_client->setClientId(OsSettingsHelper::get_settings_value('google_calendar_client_id'));
    $g_client->setClientSecret(OsSettingsHelper::get_settings_value('google_calendar_client_secret'));
    $g_client->setAccessType("offline");        // offline access
    $g_client->setIncludeGrantedScopes(true);   // incremental auth
		$g_client->setApprovalPrompt('force');
    $g_client->addScope(\LatePoint\GoogleCalendarAddon\Google\Service\Calendar::CALENDAR);
    $g_client->setRedirectUri('postmessage');
    return $g_client;
	}

  public static function get_access_token_for_agent($agent_id){
    $agent = new OsAgentModel($agent_id);
		$access_token = $agent->get_meta_by_key('google_cal_access_token', false);
		return ($access_token) ? json_decode($access_token, true) : false;
  }

  public static function get_g_service_for_agent($agent_id){
    $g_client = self::get_authorized_client_for_agent($agent_id);
    $g_service = false;
    if($g_client){
      try{
        $g_service = new \LatePoint\GoogleCalendarAddon\Google\Service\Calendar($g_client);
      }catch(Exception $e){
        OsDebugHelper::log('Error getting Google Service: '.$e->getMessage(), 'google_calendar_error');
      }
    }
    return $g_service;
  }

	/**
	 * @param int $agent_id
	 * @param array $what_to_keep
	 * @return void
	 *
	 * Clears google calendar connection info from LatePoint database.
	 * You can optionally pass array of things to keep:  ['token', 'calendar_id_push', 'calendar_ids_pull']
	 */
	public static function clear_calendar_connection_info_from_agent(int $agent_id, array $what_to_keep = []){
		$agent = new OsAgentModel($agent_id);
		if($agent){
      if(!in_array('token', $what_to_keep)) $agent->delete_meta_by_key('google_cal_access_token');
      if(!in_array('calendar_id_push', $what_to_keep)) $agent->delete_meta_by_key('google_cal_selected_calendar_id_for_push');
      if(!in_array('calendar_ids_pull', $what_to_keep)) $agent->delete_meta_by_key('google_cal_selected_calendar_ids_for_pull');
		}
	}

	public static function get_authorized_client_for_agent($agent_id){
    $access_token = self::get_access_token_for_agent($agent_id);
    if(!$access_token) return false;
		try {
	    $g_client = OsGoogleCalendarHelper::get_client();
	    $g_client->setAccessToken($access_token);
		}catch (GuzzleHttp\Exception\ConnectException $e) {
			// connection error, don't disconnect agent from the calendar, maybe it's just a temporary connection issue
			OsDebugHelper::log('Google Calendar Connect Error (Temp)', 'google_calendar_error', ['exception' => $e->getMessage()]);
			return false;
		}catch (Exception $e) {
			OsDebugHelper::log('Google Calendar Connect Error', 'google_calendar_error', ['exception' => $e->getMessage()]);
			self::clear_calendar_connection_info_from_agent($agent_id);
			return false;
		}

    if ($g_client->isAccessTokenExpired()) {
      // Refresh the token if possible, else fetch a new one.
      if ($g_client->getRefreshToken()) {
        $refresh_info = $g_client->fetchAccessTokenWithRefreshToken($g_client->getRefreshToken());
				if(isset($refresh_info['error'])){
					// can not refresh token, clear current connection
					OsDebugHelper::log('Google calendar token issue: '. $refresh_info['error']. ', ' .($refresh_info['description'] ?? 'No Description'), 'google_calendar_error');
					self::clear_calendar_connection_info_from_agent($agent_id);
				}else{
					// token refreshed
		      $new_access_token = $g_client->getAccessToken();
		      OsMetaHelper::save_agent_meta_by_key('google_cal_access_token', json_encode($new_access_token), $agent_id);
				}
      } else {
				self::clear_calendar_connection_info_from_agent($agent_id);
	      OsDebugHelper::log('Google Calendar Access/Refresh Token expired.', 'google_calendar_error');
				return false;
      }
    }

    return $g_client;

	}
}
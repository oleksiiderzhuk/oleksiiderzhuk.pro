<?php
//Определяем размер фотографии — ширину и высоту
$size=GetImageSize ("pic.jpg");
if ($size){echo 'ok'.$size;}
//Создаём новое изображение из «старого»
$src=ImageCreateFromJPEG ("pic.jpg");
if ($src){echo '<br><br>ok'.$src;}
//Берём числовое значение ширины фотографии, которое мы получили в первой строке и записываем это число в переменную
$iw=$size[0];
echo '<br>'.$iw.'<br>';
//Проделываем ту же операцию, что и в предыдущей строке, но только уже с высотой.
$ih=$size[1];
echo '<br>'.$ih.'<br>';
//Ширину фотографии делим на 150 т.к. на выходе мы хотим получить фото шириной в 150 пикселей. В результате получаем коэфициент соотношения ширины оригинала с будущей превьюшкой.
$koe=$iw/150;
//Делим высоту изображения на коэфициент, полученный в предыдущей строке, и округляем число до целого в большую сторону — в результате получаем высоту нового изображения.
$new_h=ceil ($ih/$koe);
//Создаём пустое изображение шириной в 150 пикселей и высотой, которую мы вычислили в предыдущей строке.
$dst=ImageCreateTrueColor (150, $new_h);
//Данная функция копирует прямоугольную часть изображения в другое изображение, плавно интерполируя пикселные значения таким образом, что, в частности, уменьшение размера изображения сохранит его чёткость и яркость.
ImageCopyResampled ($dst, $src, 0, 0, 0, 0, 150, $new_h, $iw, $ih);
//Сохраняем полученное изображение в формате JPG
ImageJPEG ($dst, "small_pic.jpg", 100);
imagedestroy($src);
?>
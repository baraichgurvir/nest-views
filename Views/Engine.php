<?php
namespace Nest\Views;


class Engine
{
   private static $vars = [];

   public static function Assign(array $variables) {
      foreach ($variables as $key => $var) {
         Engine::$vars[$key] = $var;
      }
   }


   public static function Render(string $template) {
      $file = exec('pwd') . "/Public/Views/$template.nest";

      if (file_exists($file)) {
         $ct = file_get_contents($file);
         $ct = preg_replace('/(\$.*)/mi', '{{$1}}', $ct);
         $code = "";

         preg_match_all('/[^\s]+/m', $ct, $contents, PREG_SET_ORDER, 0);
         
         foreach ($contents as $key => $content) {
            foreach (Engine::$vars as $var => $value) {
               $content = preg_replace('/{{\$'. $var .'}}/', "$value", $content[0]);
            }

            $code .= $content;
            $content = "";
         }

         eval(" ?> $code <?php");
      } else {
         echo "<h1>File Not Found</h1>";
      }
   }
}

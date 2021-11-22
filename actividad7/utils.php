<?php
function starts_with(string $src, string $prefix): string
{
  return mb_substr($src, 0, mb_strlen($prefix)) === $prefix;
}

function remove_prefix(string $src, string $prefix): string
{
  if (starts_with($src, $prefix)) {
    return substr($src, strlen($prefix));
  } else {
    return $src;
  }
}

function xss_escape(string $s)
{
  return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_DISALLOWED);
}

function obtener_fecha_legible(DateTime $fecha)
{
  $ahora = new DateTime();
  $diff = $ahora->diff($fecha, true);

  $result = $fecha->format('Y-m-d');

  if ($diff->y > 0 || $diff->m > 0) {
  } else {
    if ($diff->d >= 2 || ($diff->d == 1 && $diff->h > intval($ahora->format('H')))) {
      $result = "Hace {$diff->d} días";
    } else {
      $hora = $fecha->format('H:i');

      if ($diff->d >= 1 || $diff->h > intval($ahora->format('H'))) {
        $result = "Ayer a las $hora";
      } else {
        $result = "Hoy a las $hora";
      }
    }
  }

  return $result;
}

function fecha_de_sql(string $fecha)
{
  return DateTime::createFromFormat("Y-m-d H:i:s", $fecha);
}

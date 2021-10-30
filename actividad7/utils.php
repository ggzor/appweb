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

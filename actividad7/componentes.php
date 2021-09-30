<?php

function links($is_admin = false)
{
  echo '
<section class="links">';

  if ($is_admin) {
    echo '<p class="admin-indicator">Administrador</p>';
  }

  echo '
  <a href="logout.php">Cerrar sesión</a>
</section>
';
}

function logo()
{
  return <<<EOF
<a href="index.php" class="logo">
  <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 46 50">
    <path d="M22 48l-2 2H8c-4 0-7-4-7-8V8c0-4 3-8 7-8h24c5 0 8 4 8
            8v21a2 2 0 01-4 0V8c0-2-1-4-4-4H8C6 4 5 6 5 8v34c0 2
            1 4 3 4h12l2 2zm23-16h-3L32 46h-1l-7-6a2 2 0 00-2 3l6
            6a5 5 0 007-1l10-13v-3zM31 12H10a2 2 0 100 4h21a2 2 0
            000-4zm1 9l-1-1H10a2 2 0 100 3h21l1-2zm-22 6a2 2 0 100
            4h13a2 2 0 100-4H10z" />
  </svg>
  <p>
    examen<b>es</b>
  </p>
</a>
EOF;
}

function menu($options)
{
  ['items' => $items, 'selected' => $selected] = $options;

  echo '
<section class="menu">
  <p>Menú</p>
  ';

  for ($i = 0; $i < count($items); $i++) {
    echo $items[$i]($i === $selected);
  }

  echo '
</section>
  ';
}

$m_history = function ($selected) {
  $cls = $selected ? "seleccionado" : "";

  return <<<EOF
<a class="$cls" href="history.php">
  <svg width="32" height="32" xmlns="http://www.w3.org/2000/svg">
    <g clip-path="url(#a)">
      <path d="M18 2C10.961 2 5.135 7.228 4.16 14H0l6 6 6-6H8.202c.93-4.559 
               4.97-8 9.798-8 5.514 0 10 4.486 10 10s-4.486 10-10 10a10.036 
               10.036 0 0 1-8.121-4.162l-3.246 2.336A14.042 14.042 0 0 0 18 
               30c7.72 0 14-6.281 14-14S25.72 2 18 2Z" />
      <path d="M16 10v8.133l5.97 3.582 2.06-3.43L20 15.867V10h-4Z" />
    </g>
    <defs>
      <clipPath id="a">
        <path d="M0 0h32v32H0z" />
      </clipPath>
    </defs>
  </svg>
  Historial
</a>
EOF;
};


$m_create = function ($selected) {
  $cls = $selected ? "seleccionado" : "";

  return <<<EOF
<a class="$cls" href="create.php">
    <svg width="32" height="32" xmlns="http://www.w3.org/2000/svg">
      <g clip-path="url(#a)">
        <path d="M2.926 31.725a.937.937 0 0 0 1.326 0l12.391-12.39.195 
                  1.636a.938.938 0 0 0 1.682.45l3.131-4.185 4.805 2.06a.938.938 0 
                  0 0 1.23-1.232l-2.058-4.804 4.185-3.131a.938.938 0 0 
                  0-.45-1.682l-5.19-.619-.62-5.19a.938.938 0 0 0-1.681-.45L18.74 
                  6.372l-4.804-2.06a.937.937 0 0 0-1.231 1.232l2.059 4.804-4.185 
                  3.131a.937.937 0 0 0 .45 1.682l1.637.195L.274 27.748a.938.938 0 
                  0 0 0 1.326l2.652 2.651Zm13.552-20.317a.937.937 0 0 0 .3-1.12L15.352 
                  6.96l3.328 1.427c.398.17.86.046 1.12-.3l2.169-2.9.429 
                  3.596c.05.43.39.768.82.82l3.595.428-2.9 2.17a.937.937 0 0 0-.3 1.12l1.427 
                  3.327-3.328-1.426a.937.937 0 0 0-1.12.3l-2.17 2.9-.428-3.596a.937.937 0 0 
                  0-.82-.82l-3.596-.429 2.9-2.169Zm-1.443 4.231 1.184.142.141 1.184L3.59 
                  29.737 2.263 28.41l12.772-12.772Z" />
        <path d="m27.488 3.186-1.326 1.326a.938.938 0 0 0 1.326 1.326l1.325-1.326a.937.937 
                 0 1 0-1.325-1.326ZM17.041.032a.937.937 0 0 0-.663 1.148l.486 1.811a.937.937 
                 0 1 0 1.81-.485L18.19.695a.938.938 0 0 0-1.148-.663ZM22.38 19.954a.937.937 0 
                 0 0-.663 1.148l.485 1.812a.937.937 0 1 0 1.81-.486l-.484-1.81a.937.937 0 0 
                 0-1.149-.664ZM9.572 7.987a.938.938 0 0 0-.486 1.811l1.812.485a.937.937 0 1 0 
                 .485-1.81l-1.811-.486ZM31.305 13.81l-1.811-.485a.938.938 0 0 0-.486 
                 1.811l1.811.486a.938.938 0 0 0 .486-1.812Z" />
      </g>
      <defs>
        <clipPath id="a">
          <path d="M0 0h32v32H0z" />
        </clipPath>
      </defs>
    </svg>
    Crear examen
  </a>
EOF;
};

$m_about = function ($selected) {
  $cls = $selected ? "seleccionado" : "";
  return <<<EOF
<a class="$cls" href="about.php">
  <svg width="32" height="32" xmlns="http://www.w3.org/2000/svg">
    <path d="M16 25.219a1.563 1.563 0 1 0 0-3.125 1.563 1.563 0 0 0 0 3.125Z" />
    <path d="M16 0C7.157 0 0 7.156 0 16c0 8.843 7.156 16 16 16 8.843 0 16-7.156 
              16-16 0-8.843-7.156-16-16-16Zm0 29.5C8.539 29.5 2.5 23.462 2.5 16 
              2.5 8.539 8.538 2.5 16 2.5c7.461 0 13.5 6.038 13.5 13.5 0 7.461-6.038 
              13.5-13.5 13.5Z" />
    <path d="M16 8.031c-2.757 0-5 2.243-5 5a1.25 1.25 0 1 0 2.5 0c0-1.378 1.121-2.5 
              2.5-2.5 1.378 0 2.5 1.122 2.5 2.5 0 1.379-1.122 2.5-2.5 2.5-.69 0-1.25.56-1.25 
              1.25v3.125a1.25 1.25 0 1 0 2.5 0v-2.033A5.01 5.01 0 0 0 21 13.031c0-2.757-2.243-5-5-5Z" />
  </svg>
  Acerca de
</a>
EOF;
};


$m_questions = function ($selected) {
  $cls = $selected ? "seleccionado" : "";

  return <<<EOF
<a class="$cls" href="questions.php">
  <svg height="32" viewBox="0 0 480.001 480" width="32" xmlns="http://www.w3.org/2000/svg">
    <path
      d="m195.09 188.8 16.8 10.071a7.998 7.998 0 0 0 10.852-2.809 8 8 0 0 0-2.613-10.902l-14.848-8.918A77.82 77.82 0 0 0 216 136c0-35.29-21.527-64-48-64s-48 28.71-48 64 21.527 64 48 64a40.006 40.006 0 0 0 27.09-11.2zM136 136c0-26.016 14.656-48 32-48s32 21.984 32 48a62.413 62.413 0 0 1-8.43 32l-11.449-6.863a7.998 7.998 0 0 0-10.851 2.808 7.996 7.996 0 0 0 2.613 10.903l8.613 5.168A22.58 22.58 0 0 1 168 184c-17.344 0-32-21.984-32-48zM319.535 269.313a7.997 7.997 0 0 0-15.07 0l-40 112a8.002 8.002 0 0 0 4.848 10.222 7.997 7.997 0 0 0 10.222-4.847L291.93 352h40.144l12.39 34.688A7.997 7.997 0 0 0 352 392a7.989 7.989 0 0 0 6.54-3.39 7.992 7.992 0 0 0 .995-7.297zM297.641 336 312 295.785 326.402 336zm0 0" />
    <path
      d="M448.227 396.29A128.985 128.985 0 0 0 480 312c0-72.598-63.71-133.598-146.664-142.625A123.38 123.38 0 0 0 336 144C336 64.602 260.64 0 168 0S0 64.602 0 144a128.377 128.377 0 0 0 27.313 78.602L.52 293.16A8.003 8.003 0 0 0 8 304a8.045 8.045 0 0 0 3.2-.672l75.57-33.23a183.596 183.596 0 0 0 59.878 16.511A123.514 123.514 0 0 0 144 312c0 79.402 75.36 144 168 144a191.436 191.436 0 0 0 74.695-14.984l81.922 38.234a8 8 0 0 0 10.735-10.402zM83.747 253.96l-61.49 27.032 21.598-56.953a7.994 7.994 0 0 0-1.285-7.902A113.228 113.228 0 0 1 16 144C16 73.426 84.184 16 168 16s152 57.426 152 128c0 10.219-1.46 20.387-4.336 30.191C299.2 231.785 238.441 272 168 272c-3.605 0-7.152-.07-10.902-.383a168.87 168.87 0 0 1-66.543-17.48 8.005 8.005 0 0 0-6.809-.176zm306.437 171.032a7.996 7.996 0 0 0-6.68-.039A174.803 174.803 0 0 1 312 440c-83.816 0-152-57.422-152-128 .004-8.098.918-16.172 2.73-24.063 1.75.063 3.504.063 5.27.063 4.77 0 9.496-.172 14.176-.512 1.601-.113 3.129-.343 4.707-.496 3.07-.297 6.148-.566 9.176-1 1.863-.273 3.68-.656 5.52-.976 2.679-.465 5.374-.895 8-1.473 1.956-.422 3.862-.965 5.788-1.445 2.457-.618 4.93-1.2 7.348-1.907 1.965-.574 3.879-1.261 5.82-1.902 2.297-.762 4.61-1.488 6.86-2.328 1.94-.727 3.832-1.55 5.742-2.344 2.152-.887 4.32-1.762 6.398-2.719 1.906-.882 3.746-1.851 5.602-2.785 2.015-1.008 4.039-2.008 6-3.09 1.96-1.078 3.625-2.117 5.418-3.199 1.789-1.078 3.765-2.246 5.597-3.441 1.832-1.192 3.465-2.399 5.176-3.598 1.715-1.2 3.48-2.473 5.168-3.77s3.266-2.64 4.871-4c1.61-1.359 3.203-2.687 4.754-4.085 1.55-1.403 3.04-2.875 4.527-4.336 1.489-1.465 2.938-2.899 4.336-4.395 1.403-1.496 2.786-3.086 4.137-4.664a152.877 152.877 0 0 0 3.91-4.703c1.266-1.59 2.496-3.289 3.707-4.96 1.207-1.673 2.399-3.313 3.473-5.009 1.07-1.695 2.191-3.445 3.2-5.207 1.007-1.758 2.054-3.527 3.015-5.336a140.07 140.07 0 0 0 2.726-5.406 134.57 134.57 0 0 0 2.551-5.672 116.151 116.151 0 0 0 2.203-5.601c.352-.961.797-1.871 1.117-2.84C405.234 192 464 246.84 464 312a113.961 113.961 0 0 1-31.04 77.418 8 8 0 0 0-1.433 8.535l24.852 57.926zm0 0" />
  </svg>
  Mis reactivos
</a>
EOF;
};
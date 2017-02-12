<?php

include "../html/dblogin.php";

// These console names must match what's already in the DB
$consoles = ["Wii U", 
      "Wii U Discs", 
      "eShop (Wii U)", 
      "Virtual Console (Wii U)", 
      "Xbox 360", 
      "Xbox 360 Discs",
      "Xbox Live Arcade", 
      "Xbox Live Indie Games",
      "PlayStation 3",
      "PlayStation 3 Discs",
      "PlayStation Network (PS3)",
      "PlayStation Classics (PS3)",
      "Wii",
      "Wii Discs",
      "WiiWare",
      "Virtual Console (Wii)",
      "Xbox",
      "PlayStation 2",
      "GameCube",
      "PlayStation",
      "Nintendo 64",
      "Sega Genesis",
      "SNES",
      "NES",
      "Nintendo 3DS",
      "Nintendo 3DS Carts",
      "eShop (3DS)",
      "Virtual Console (3DS)",
      "DSiWare",
      "PlayStation Portable",
      "PlayStation Network (PSP)",
      "Nintendo DS",
      "Game Boy Advance",
      "Game Boy Advance Carts",
      "e-Reader",
      "Game Boy Color",
      "Game Boy",
      "PC",
      "PC Discs",
      "PC Downloads",
      "Steam",
      "Android"
      ];

for ($i = 0; $i < count($consoles); $i++) {
      $sql = "UPDATE consoles SET `order` = {$i} WHERE name = '{$consoles[$i]}'";
      mysqli_query($db, $sql);
}
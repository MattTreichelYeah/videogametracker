USE videogames2;
SELECT g.name, 
c.name_short AS console_name, 
c2.name_short AS console_original_name, 
g2.name AS compilation_name, 
g3.name AS dlc_name,
gt.tagid AS tag
FROM games AS g
LEFT JOIN consoles AS c ON g.console = c.id
LEFT JOIN consoles AS c2 ON g.original_console = c2.id
LEFT JOIN games AS g2 ON g.compilation_root = g2.id
LEFT JOIN games AS g3 ON g.dlc_root = g3.id
LEFT JOIN games_tags AS gt ON g.id = gt.gameid
LEFT JOIN tags AS t ON t.id = gt.tagid
WHERE gt.tagid = 1 OR gt.tagid = 13
GROUP BY g.id
HAVING COUNT(*) = 2

/* 1 13 - 1*/
/* 6 12 - 197 */
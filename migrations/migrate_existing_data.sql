-- Migration : Migrer les données existantes vers la nouvelle structure
-- Date : 2025-01-15
-- Ce script crée des volumes à partir des années présentes dans revues.date_publication

-- Créer des volumes pour chaque année unique dans revues.date_publication
INSERT INTO `volumes` (`annee`, `numero_volume`, `description`, `created_at`, `updated_at`)
SELECT DISTINCT
    CASE 
        WHEN date_publication REGEXP '^[0-9]{4}$' THEN CAST(date_publication AS UNSIGNED)
        WHEN date_publication REGEXP '[0-9]{4}' THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(date_publication, '-', 1), ' ', -1) AS UNSIGNED)
        WHEN date_publication LIKE '%2025%' THEN 2025
        WHEN date_publication LIKE '%2024%' THEN 2024
        WHEN date_publication LIKE '%2023%' THEN 2023
        WHEN date_publication LIKE '%2022%' THEN 2022
        WHEN date_publication LIKE '%2021%' THEN 2021
        WHEN date_publication LIKE '%2020%' THEN 2020
        WHEN date_publication LIKE '%2019%' THEN 2019
        WHEN date_publication LIKE '%2018%' THEN 2018
        WHEN date_publication LIKE '%2017%' THEN 2017
        WHEN date_publication LIKE '%2016%' THEN 2016
        WHEN date_publication LIKE '%2015%' THEN 2015
        WHEN date_publication LIKE '%2014%' THEN 2014
        WHEN date_publication LIKE '%2013%' THEN 2013
        WHEN date_publication LIKE '%2012%' THEN 2012
        WHEN date_publication LIKE '%2011%' THEN 2011
        WHEN date_publication LIKE '%2010%' THEN 2010
        WHEN date_publication LIKE '%2009%' THEN 2009
        WHEN date_publication LIKE '%2008%' THEN 2008
        WHEN date_publication LIKE '%2007%' THEN 2007
        WHEN date_publication LIKE '%2006%' THEN 2006
        WHEN date_publication LIKE '%2005%' THEN 2005
        WHEN date_publication LIKE '%2004%' THEN 2004
        WHEN date_publication LIKE '%2003%' THEN 2003
        WHEN date_publication LIKE '%2002%' THEN 2002
        WHEN date_publication LIKE '%2001%' THEN 2001
        WHEN date_publication LIKE '%2000%' THEN 2000
        WHEN date_publication LIKE '%1999%' THEN 1999
        WHEN date_publication LIKE '%1998%' THEN 1998
        WHEN date_publication LIKE '%1997%' THEN 1997
        WHEN date_publication LIKE '%1996%' THEN 1996
        WHEN date_publication LIKE '%1995%' THEN 1995
        WHEN date_publication LIKE '%1994%' THEN 1994
        WHEN date_publication LIKE '%1993%' THEN 1993
        WHEN date_publication LIKE '%1992%' THEN 1992
        WHEN date_publication LIKE '%1991%' THEN 1991
        WHEN date_publication LIKE '%1990%' THEN 1990
        WHEN date_publication LIKE '%1989%' THEN 1989
        WHEN date_publication LIKE '%1988%' THEN 1988
        WHEN date_publication LIKE '%1987%' THEN 1987
        WHEN date_publication LIKE '%1986%' THEN 1986
        ELSE NULL
    END as annee,
    CONCAT('Volume ', 
        CASE 
            WHEN date_publication REGEXP '^[0-9]{4}$' THEN CAST(date_publication AS UNSIGNED) - 1985
            WHEN date_publication REGEXP '[0-9]{4}' THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(date_publication, '-', 1), ' ', -1) AS UNSIGNED) - 1985
            ELSE NULL
        END
    ) as numero_volume,
    CONCAT('Volume pour l\'année ', 
        CASE 
            WHEN date_publication REGEXP '^[0-9]{4}$' THEN date_publication
            WHEN date_publication REGEXP '[0-9]{4}' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(date_publication, '-', 1), ' ', -1)
            ELSE date_publication
        END
    ) as description,
    NOW(),
    NOW()
FROM `revues`
WHERE date_publication IS NOT NULL
  AND date_publication != ''
GROUP BY annee
HAVING annee IS NOT NULL
ON DUPLICATE KEY UPDATE `numero_volume` = VALUES(`numero_volume`);

-- Lier les revues existantes aux volumes correspondants (si volume_id n'est pas déjà rempli)
UPDATE `revues` r
LEFT JOIN `volumes` v ON (
    CASE 
        WHEN r.date_publication REGEXP '^[0-9]{4}$' THEN v.annee = CAST(r.date_publication AS UNSIGNED)
        WHEN r.date_publication REGEXP '[0-9]{4}' THEN v.annee = CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(r.date_publication, '-', 1), ' ', -1) AS UNSIGNED)
        WHEN r.date_publication LIKE '%2025%' THEN v.annee = 2025
        WHEN r.date_publication LIKE '%2024%' THEN v.annee = 2024
        WHEN r.date_publication LIKE '%2023%' THEN v.annee = 2023
        WHEN r.date_publication LIKE '%2022%' THEN v.annee = 2022
        WHEN r.date_publication LIKE '%2021%' THEN v.annee = 2021
        WHEN r.date_publication LIKE '%2020%' THEN v.annee = 2020
        WHEN r.date_publication LIKE '%2019%' THEN v.annee = 2019
        WHEN r.date_publication LIKE '%2018%' THEN v.annee = 2018
        WHEN r.date_publication LIKE '%2017%' THEN v.annee = 2017
        WHEN r.date_publication LIKE '%2016%' THEN v.annee = 2016
        WHEN r.date_publication LIKE '%2015%' THEN v.annee = 2015
        WHEN r.date_publication LIKE '%2014%' THEN v.annee = 2014
        WHEN r.date_publication LIKE '%2013%' THEN v.annee = 2013
        WHEN r.date_publication LIKE '%2012%' THEN v.annee = 2012
        WHEN r.date_publication LIKE '%2011%' THEN v.annee = 2011
        WHEN r.date_publication LIKE '%2010%' THEN v.annee = 2010
        WHEN r.date_publication LIKE '%2009%' THEN v.annee = 2009
        WHEN r.date_publication LIKE '%2008%' THEN v.annee = 2008
        WHEN r.date_publication LIKE '%2007%' THEN v.annee = 2007
        WHEN r.date_publication LIKE '%2006%' THEN v.annee = 2006
        WHEN r.date_publication LIKE '%2005%' THEN v.annee = 2005
        WHEN r.date_publication LIKE '%2004%' THEN v.annee = 2004
        WHEN r.date_publication LIKE '%2003%' THEN v.annee = 2003
        WHEN r.date_publication LIKE '%2002%' THEN v.annee = 2002
        WHEN r.date_publication LIKE '%2001%' THEN v.annee = 2001
        WHEN r.date_publication LIKE '%2000%' THEN v.annee = 2000
        WHEN r.date_publication LIKE '%1999%' THEN v.annee = 1999
        WHEN r.date_publication LIKE '%1998%' THEN v.annee = 1998
        WHEN r.date_publication LIKE '%1997%' THEN v.annee = 1997
        WHEN r.date_publication LIKE '%1996%' THEN v.annee = 1996
        WHEN r.date_publication LIKE '%1995%' THEN v.annee = 1995
        WHEN r.date_publication LIKE '%1994%' THEN v.annee = 1994
        WHEN r.date_publication LIKE '%1993%' THEN v.annee = 1993
        WHEN r.date_publication LIKE '%1992%' THEN v.annee = 1992
        WHEN r.date_publication LIKE '%1991%' THEN v.annee = 1991
        WHEN r.date_publication LIKE '%1990%' THEN v.annee = 1990
        WHEN r.date_publication LIKE '%1989%' THEN v.annee = 1989
        WHEN r.date_publication LIKE '%1988%' THEN v.annee = 1988
        WHEN r.date_publication LIKE '%1987%' THEN v.annee = 1987
        WHEN r.date_publication LIKE '%1986%' THEN v.annee = 1986
        ELSE FALSE
    END
)
SET r.volume_id = v.id, r.type = 'issue'
WHERE r.volume_id IS NULL
  AND v.id IS NOT NULL;


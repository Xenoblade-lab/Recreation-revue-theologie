<?php
/**
 * Script à exécuter une fois : met à jour le Comité scientifique et de Rédaction
 * dans revue_info (sans les personnes avec la mention C.T. / CT.).
 *
 * Usage : depuis la racine du projet (revue-theologie-upc-html)
 *   php scripts/update_comite_scientifique_redaction.php
 *
 * Ou via navigateur (si le serveur exécute le dossier parent) :
 *   http://localhost/.../revue-theologie-upc-html/scripts/update_comite_scientifique_redaction.php
 */

$baseDir = dirname(__DIR__);
if (!is_file($baseDir . '/config/config.php')) {
    fwrite(STDERR, "Erreur : exécuter depuis la racine du projet (revue-theologie-upc-html).\n");
    exit(1);
}

require_once $baseDir . '/config/config.php';
require_once $baseDir . '/includes/db.php';
require_once $baseDir . '/models/RevueInfoModel.php';

// Liste du comité scientifique et de rédaction — sans les C.T. / CT.
$listeComite = "Prof. N'KWIM Bibi-Bikan Robert, Prof. Em. MUNAYI Muntu-Monji Thomas, Prof. BOKUNDOA Bo-Likabe André-Gédéon, Prof. MEME Dingadie Monger Andermon, Prof. KALOMBO Kapuku Sébastien, Prof. MUENYI Kamwinga Honoré, Prof. VIBILA Vuadi Liz, Prof. MUSHAGALUSA Baciyunjuze Timothée, Prof. MAKUTA Likombe Bijoux, Prof. KABUE Mbala Simon, Prof. ANGENDU Mongenzo Raymond, Prof. NGANGURA ManYanya Lévi, Prof. SANGUMA T. Mossai, Prof. N'LANDU Moyo Esther, Prof. RAZANAD-RAKOTO A. Haritsima, Prof. KABASELE Mukenge André, Prof. NGALULA Tshianda José, Prof. M'BULU Zola-di-Muanzabang Jean, Prof. KIAZAYILA Kingengo Pierre, Prof. ABEL Olivier, Prof. FATH Sébastien, Prof. ALIANGO Marachto Dédé, Prof. ALIPANAZANGA Atan-Igamu Faustin, Prof. NGONGO Kilongo Fatuma, Prof. MANDEFU Buanga Jeannot, Prof. BAKENGELA Shamba Patric, Prof. KALALA Tshimpaka Frederic, Prof. Johann Udo Steffens, Prof. KISUKULU Kayembelyuba Don Yves, Prof. LYANDJA Betulu Jean-Calvin et Monsieur NGAIE Jean-Victor.";

$info = \Models\RevueInfoModel::get();
if (!$info) {
    fwrite(STDERR, "Erreur : aucune ligne revue_info (id = 1) trouvée.\n");
    exit(1);
}

$ok = \Models\RevueInfoModel::update(
    $info['nom_officiel'] ?? '',
    $info['description'] ?? null,
    $info['ligne_editoriale'] ?? null,
    $info['objectifs'] ?? null,
    $info['domaines_couverts'] ?? null,
    $info['issn'] ?? null,
    $listeComite,  // comite_scientifique
    $listeComite   // comite_redaction
);

if ($ok) {
    echo "Comité scientifique et de Rédaction mis à jour (sans les C.T.).\n";
    echo "Vérifiez la page /comite sur le site.\n";
} else {
    fwrite(STDERR, "Erreur lors de la mise à jour.\n");
    exit(1);
}

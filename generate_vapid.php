<?php
/**
 * Générateur de clés VAPID autonome
 * Contourne le bug de web-token/jwt-library sur Windows
 */

echo "🔐 Génération des clés VAPID...\n\n";

// ============================================
// 1. Générer une clé EC P-256 avec OpenSSL natif
// ============================================
$config = [
    'curve_name'       => 'prime256v1',
    'private_key_type' => OPENSSL_KEYTYPE_EC,
];

$resource = openssl_pkey_new($config);

if ($resource === false) {
    die("❌ Erreur OpenSSL : " . openssl_error_string() . "\n");
}

// ============================================
// 2. Extraire la clé privée au format PEM
// ============================================
openssl_pkey_export($resource, $privateKeyPem);

// ============================================
// 3. Extraire la clé publique
// ============================================
$details = openssl_pkey_get_details($resource);

if (! isset($details['ec']['x']) || ! isset($details['ec']['y'])) {
    die("❌ Impossible d'extraire les coordonnées EC.\n");
}

// ============================================
// 4. Encoder les clés en base64url (format VAPID)
// ============================================

/**
 * Encode en base64url (RFC 4648 §5) sans padding.
 */
function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

// Clé publique VAPID = 0x04 || x || y (format uncompressed point)
$publicKeyRaw = "\x04" . $details['ec']['x'] . $details['ec']['y'];
$publicKeyB64 = base64url_encode($publicKeyRaw);

// Clé privée VAPID = juste la valeur "d"
$privateKeyB64 = base64url_encode($details['ec']['d']);

// ============================================
// 5. Affichage
// ============================================
echo "✅ Clés générées avec succès !\n\n";
echo "Copie ces 3 lignes dans ton fichier .env :\n";
echo "═══════════════════════════════════════════════════════\n";
echo "VAPID_PUBLIC_KEY=\"" . $publicKeyB64 . "\"\n";
echo "VAPID_PRIVATE_KEY=\"" . $privateKeyB64 . "\"\n";
echo "VAPID_SUBJECT=\"mailto:contact@monmaitre.ci\"\n";
echo "═══════════════════════════════════════════════════════\n\n";

// ============================================
// 6. Vérification
// ============================================
echo "📋 Détails techniques :\n";
echo "- Courbe : prime256v1 (P-256)\n";
echo "- Longueur clé publique : " . strlen($publicKeyB64) . " caractères (attendu : ~87)\n";
echo "- Longueur clé privée : " . strlen($privateKeyB64) . " caractères (attendu : ~43)\n";
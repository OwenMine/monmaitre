/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                // ========================================
                // PALETTE OFFICIELLE MON MAÎTRE
                // ========================================
                "mm-primary": "#0B3D91", // Bleu principal (marque, titres)
                "mm-navy": "#062B5C", // Bleu marine (fonds foncés, admin)
                "mm-secondary": "#143B8F", // Bleu secondaire (répétiteur)
                "mm-accent": "#F5A623", // Or / Doré (accent, CTA)
                "mm-white": "#FFFFFF",
                "mm-anthracite": "#1E1E1E", // Texte foncé
                "mm-light": "#F5F6FA", // Gris clair (fonds de pages)

                // Hovers dérivés
                "mm-navy-hover": "#041E40",
                "mm-secondary-hover": "#0F2E6F",
                "mm-accent-hover": "#D98F1F",
            },
            fontFamily: {
                sans: ["Inter", "system-ui", "sans-serif"],
            },
        },
    },
    plugins: [],
};

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            animation: {
                gradient: "gradient 20s ease infinite",
                float: "float 4s ease-in-out infinite",
                "pulse-slow": "pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite",
                "bounce-slow": "bounce 3s infinite",
                wiggle: "wiggle 1s ease-in-out infinite",
                "slide-in": "slideIn 0.4s ease-out",
                "fade-in": "fadeIn 0.5s ease-out",
                "scale-in": "scaleIn 0.3s ease-out",
                "spin-slow": "spin 4s linear infinite",
            },
            keyframes: {
                gradient: {
                    "0%, 100%": {
                        backgroundPosition: "0% 50%",
                    },
                    "50%": {
                        backgroundPosition: "100% 50%",
                    },
                },
                float: {
                    "0%, 100%": {
                        transform: "translateY(0px)",
                    },
                    "50%": {
                        transform: "translateY(-10px)",
                    },
                },
                wiggle: {
                    "0%, 100%": {
                        transform: "rotate(-3deg)",
                    },
                    "50%": {
                        transform: "rotate(3deg)",
                    },
                },
                slideIn: {
                    "0%": {
                        transform: "translateX(-100%)",
                        opacity: "0",
                    },
                    "100%": {
                        transform: "translateX(0)",
                        opacity: "1",
                    },
                },
                fadeIn: {
                    "0%": {
                        opacity: "0",
                        transform: "translateY(20px)",
                    },
                    "100%": {
                        opacity: "1",
                        transform: "translateY(0)",
                    },
                },
                scaleIn: {
                    "0%": {
                        transform: "scale(0.9)",
                        opacity: "0",
                    },
                    "100%": {
                        transform: "scale(1)",
                        opacity: "1",
                    },
                },
            },
        },
    },
    plugins: [],
};

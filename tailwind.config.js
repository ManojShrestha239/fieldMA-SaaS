/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/views/super-admin/**/*.blade.php",
    "./resources/views/super-admin/*.blade.php",
    "./resources/js/super-admin/**/*.{js,jsx,ts,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        admin: {
          // 60% Dominant (Canvas / Main Background)
          canvas: '#F8FAFC',        // Slate-50: Main app background & surrounding space
          canvasDark: '#F1F5F9',    // Slate-100: Inner subtle backgrounds

          // 30% Structural (Surfaces, Cards, Sidebar & Top Navigation)
          surface: '#FFFFFF',       // Pure White: Cards, modal surfaces, table background
          border: '#E2E8F0',        // Slate-200: Soft structural borders & card dividers
          borderSubtle: '#F1F5F9',  // Slate-100: Table row dividers
          
          sidebar: {
            DEFAULT: '#1E1B4B',     // Deep Indigo: Brand sidebar base
            hover: 'rgba(255, 255, 255, 0.08)',
            active: 'rgba(255, 255, 255, 0.14)',
            activeBorder: '#C0392B', // Accent indicator on active tab
            text: '#94A3B8',        // Slate-400: Inactive nav text
            textHover: '#FFFFFF',   // Pure White: Hover nav text
            textActive: '#FFFFFF',  // Pure White: Active nav text
            muted: '#64748B',       // Slate-500: Section header labels
            divider: '#2E2A72',     // Subtle divider inside indigo
          },

          header: {
            DEFAULT: '#FFFFFF',     // Pure White: Top navbar surface
            border: '#E2E8F0',      // Slate-200: Header bottom border
            text: '#0F172A',        // Slate-900: Header title & brand
            muted: '#64748B',       // Slate-500: User info & subtitle
          },

          // 10% Accent (Primary Actions, Alerts & Highlights)
          primary: {
            DEFAULT: '#C0392B',     // Logo Red: Primary CTAs, key actions
            hover: '#A93226',       // Darker Red: Hover states
            active: '#922B21',      // Pressed Red
            subtle: '#FEF2F2',      // Red-50: Light tint for alerts/badges
            border: '#FECACA',      // Red-200: Light border for alerts
            focus: 'rgba(192, 57, 43, 0.25)',
          },

          accent: {
            DEFAULT: '#F39C12',     // Logo Orange/Amber: Secondary tags, warnings, KPI badges
            hover: '#D68910',       // Darker Amber
            subtle: '#FFFBEB',      // Amber-50: Light tint
            border: '#FDE68A',      // Amber-200: Warning border
            focus: 'rgba(243, 156, 18, 0.25)',
          },

          // Typography (WCAG AA Compliant High Contrast)
          title: '#0F172A',         // Slate-900: Titles, headings, stat numbers
          body: '#334155',          // Slate-700: Body text, table values
          muted: '#64748B',         // Slate-500: Secondary labels, metadata
          subtle: '#94A3B8',        // Slate-400: Placeholders, timestamps

          // Semantic State Badges
          status: {
            active: '#10B981',      // Emerald-500
            activeBg: '#ECFDF5',    // Emerald-50
            activeText: '#047857',  // Emerald-700
            activeBorder: '#A7F3D0',// Emerald-200

            trial: '#F59E0B',       // Amber-500
            trialBg: '#FFFBEB',     // Amber-50
            trialText: '#B45309',   // Amber-700
            trialBorder: '#FDE68A', // Amber-200

            suspended: '#EF4444',   // Red-500
            suspendedBg: '#FEF2F2', // Red-50
            suspendedText: '#B91C1C',// Red-700
            suspendedBorder: '#FECACA', // Red-200

            inactive: '#64748B',    // Slate-500
            inactiveBg: '#F1F5F9',  // Slate-100
            inactiveText: '#475569',// Slate-600
            inactiveBorder: '#E2E8F0', // Slate-200
          }
        }
      },
      fontFamily: {
        sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
      },
      boxShadow: {
        'admin-card': '0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px -1px rgba(0, 0, 0, 0.04)',
        'admin-card-hover': '0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.05)',
        'admin-dropdown': '0 10px 15px -3px rgba(15, 23, 42, 0.08), 0 4px 6px -4px rgba(15, 23, 42, 0.04)',
      }
    },
  },
  plugins: [],
};

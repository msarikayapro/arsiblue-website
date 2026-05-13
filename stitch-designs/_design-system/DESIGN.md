---
name: Mediterranean Serenity
colors:
  surface: '#f7f9ff'
  surface-dim: '#d7dae0'
  surface-bright: '#f7f9ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f1f4f9'
  surface-container: '#ebeef4'
  surface-container-high: '#e6e8ee'
  surface-container-highest: '#e0e2e8'
  on-surface: '#181c20'
  on-surface-variant: '#404850'
  inverse-surface: '#2d3135'
  inverse-on-surface: '#eef1f6'
  outline: '#707881'
  outline-variant: '#bfc7d1'
  surface-tint: '#006399'
  primary: '#005d90'
  on-primary: '#ffffff'
  primary-container: '#0077b6'
  on-primary-container: '#f3f7ff'
  inverse-primary: '#94ccff'
  secondary: '#00677d'
  on-secondary: '#ffffff'
  secondary-container: '#50d9fe'
  on-secondary-container: '#005c70'
  tertiary: '#864a00'
  on-tertiary: '#ffffff'
  tertiary-container: '#a95f00'
  on-tertiary-container: '#fff6f1'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#cde5ff'
  primary-fixed-dim: '#94ccff'
  on-primary-fixed: '#001d32'
  on-primary-fixed-variant: '#004b74'
  secondary-fixed: '#b3ebff'
  secondary-fixed-dim: '#4cd6fb'
  on-secondary-fixed: '#001f27'
  on-secondary-fixed-variant: '#004e5f'
  tertiary-fixed: '#ffdcc0'
  tertiary-fixed-dim: '#ffb877'
  on-tertiary-fixed: '#2e1600'
  on-tertiary-fixed-variant: '#6c3a00'
  background: '#f7f9ff'
  on-background: '#181c20'
  surface-variant: '#e0e2e8'
typography:
  display-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 48px
    fontWeight: '700'
    lineHeight: '1.1'
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 32px
    fontWeight: '700'
    lineHeight: '1.2'
  headline-lg-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 28px
    fontWeight: '700'
    lineHeight: '1.2'
  headline-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 24px
    fontWeight: '600'
    lineHeight: '1.3'
  body-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 18px
    fontWeight: '400'
    lineHeight: '1.6'
  body-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.5'
  label-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 14px
    fontWeight: '600'
    lineHeight: '1.4'
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 8px
  section-gap-desktop: 80px
  section-gap-mobile: 48px
  container-max-width: 1200px
  gutter: 24px
  margin-mobile: 16px
---

## Brand & Style

This design system is built to evoke the warmth of an Alanya summer and the reliability of a premium family resort. The visual language balances **Corporate Modern** structure with **Tactile** softness. It prioritizes a "Family-First" philosophy, ensuring that the interface feels safe for parents and exciting for holiday-seekers.

The Mediterranean vibe is achieved through a "Sand and Sea" layout rhythm, using cream backgrounds to soften the transition between high-quality lifestyle photography. The emotional response is one of relaxation, trust, and sun-drenched professionalism. All interface copy must be in Turkish, utilizing a friendly yet formal tone (*Siz* language).

## Colors

The palette is anchored by **Sea Blue (#0077B6)**, used for primary actions to provide a sense of depth and stability. **Cream (#FAF3E0)** serves as a primary section background to mimic beach sand, reducing eye strain and differentiating the site from sterile corporate competitors.

- **Primary & Secondary:** Use for CTAs, active states, and navigation highlights.
- **Accents:** **Warm Yellow** is reserved for star ratings and seasonal offers. **Coral/Orange** is used sparingly for "Last 2 Rooms" or "Early Bird" urgency labels.
- **Neutrals:** **Navy** is strictly for the footer and deep-contrast text headers. **Dark Gray** ensures WCAG-compliant legibility for body copy.
- **Utilities:** **Green** is exclusively for the WhatsApp support float, ensuring it is instantly recognizable to Turkish travelers.

## Typography

We use **Plus Jakarta Sans** for its optimistic, rounded terminals which align with the family-friendly narrative while maintaining professional clarity. 

- **Headlines:** Use Bold (700) weight for main section titles to establish a strong visual hierarchy.
- **Body Text:** Use Regular (400) weight. Turkish characters (ç, ğ, ı, ö, ş, ü) must be checked for consistent kerning.
- **Urgency/Labels:** Small labels use Semibold (600) with slight letter spacing to maintain readability on mobile screens.
- **Mobile Scaling:** Headlines downscale by approximately 15% on mobile to ensure room for high-impact photography without pushing booking CTAs off-screen.

## Layout & Spacing

This design system employs a **fixed grid** for desktop (12 columns) and a **fluid grid** for mobile (4 columns). 

- **Rhythm:** A base-8 spacing scale drives all padding and margins. 
- **Whitespace:** Generous vertical spacing (80px on desktop) between sections prevents the "cluttered travel agency" look, emphasizing a premium 4-star experience.
- **Mobile-First:** All interactive elements (buttons, inputs) must have a minimum height of 48px for thumb-friendly navigation.
- **Composition:** Use "Checkerboard" layouts for room features—alternating photography and text blocks—to maintain engagement on long-scroll pages.

## Elevation & Depth

To mimic the soft, diffused light of the Mediterranean, this design system avoids harsh, black shadows. Instead, it utilizes **Ambient Shadows** tinted with the Primary Navy color at very low opacities (4-8%).

- **Level 1 (Cards):** Low-offset shadow (0px 4px 12px) to give a subtle lift from the Cream background.
- **Level 2 (Dropdowns/Modals):** Medium-offset (0px 10px 24px) to create distinct separation.
- **Interactive:** On hover, cards should increase in elevation slightly (y-offset increases) to provide tactile feedback to the user.
- **Depth:** Semi-transparent white overlays (glassmorphism) may be used on top of hero images to ensure text legibility while maintaining the visual connection to the imagery.

## Shapes

The shape language is consistently **Rounded (Level 2)**. A 16px (1rem) corner radius is the standard for cards, images, and primary container elements.

- **Standard Elements:** 16px radius for room cards, testimonial blocks, and photo galleries.
- **Interactive Elements:** Buttons and Input fields use a slightly smaller 12px radius to appear more precise and functional.
- **Pills:** Search filters and status tags (e.g., "Her Şey Dahil") use a fully rounded (pill) shape to distinguish them from actionable buttons.

## Components

### Buttons
- **Primary:** Sea Blue background, White text. High-contrast and prominent.
- **Secondary:** Transparent with Sea Blue border or Light Blue background.
- **Urgency:** Coral/Orange, used only for "Şimdi Rezervasyon Yap" (Book Now) in the final checkout stage.

### Cards
- **Room Cards:** 16px rounded corners, soft shadow, image at top. Price displayed in Navy bold text, positioned bottom-right.
- **Feature Icons:** Use a Light Blue circular background with Navy icons for hotel amenities (Wi-Fi, Havuz, Klima).

### Inputs & Booking Bar
- **Date Picker:** Should be large and mobile-optimized. Selected dates highlighted in Sea Blue.
- **Guest Selector:** Simple plus/minus controls with clear labels for "Yetişkin" (Adult) and "Çocuk" (Child).

### Status Indicators
- **WhatsApp Float:** Fixed to bottom-right on mobile. Green (#10B981) bubble with a white icon and "Bize Yazın" label.
- **Badges:** Use "Warm Yellow" for "En Popüler" or "Aile Dostu" badges on room listings.

### Navigation
- **Sticky Header:** White background with a subtle shadow on scroll. Large "Rezervasyon" button always visible in the top right.
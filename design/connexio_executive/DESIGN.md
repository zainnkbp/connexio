---
name: Connexio Executive
colors:
  surface: '#f8f9ff'
  surface-dim: '#cbdbf5'
  surface-bright: '#f8f9ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#eff4ff'
  surface-container: '#e5eeff'
  surface-container-high: '#dce9ff'
  surface-container-highest: '#d3e4fe'
  on-surface: '#0b1c30'
  on-surface-variant: '#444651'
  inverse-surface: '#213145'
  inverse-on-surface: '#eaf1ff'
  outline: '#757682'
  outline-variant: '#c5c5d3'
  surface-tint: '#4059aa'
  primary: '#00236f'
  on-primary: '#ffffff'
  primary-container: '#1e3a8a'
  on-primary-container: '#90a8ff'
  inverse-primary: '#b6c4ff'
  secondary: '#1960a3'
  on-secondary: '#ffffff'
  secondary-container: '#7db6ff'
  on-secondary-container: '#00477f'
  tertiary: '#3e2400'
  on-tertiary: '#ffffff'
  tertiary-container: '#5c3800'
  on-tertiary-container: '#ef9900'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dce1ff'
  primary-fixed-dim: '#b6c4ff'
  on-primary-fixed: '#00164e'
  on-primary-fixed-variant: '#264191'
  secondary-fixed: '#d3e4ff'
  secondary-fixed-dim: '#a2c9ff'
  on-secondary-fixed: '#001c38'
  on-secondary-fixed-variant: '#004881'
  tertiary-fixed: '#ffddb8'
  tertiary-fixed-dim: '#ffb95f'
  on-tertiary-fixed: '#2a1700'
  on-tertiary-fixed-variant: '#653e00'
  background: '#f8f9ff'
  on-background: '#0b1c30'
  surface-variant: '#d3e4fe'
typography:
  display-lg:
    fontFamily: Inter
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 56px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '600'
    lineHeight: 40px
    letterSpacing: -0.01em
  headline-lg-mobile:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.01em
  title-md:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
    letterSpacing: 0em
  body-lg:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
    letterSpacing: 0em
  body-sm:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
    letterSpacing: 0em
  label-caps:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.05em
  mono-data:
    fontFamily: jetbrainsMono
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
    letterSpacing: -0.01em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 4px
  xs: 8px
  sm: 16px
  md: 24px
  lg: 32px
  xl: 48px
  container-max: 1440px
  gutter: 24px
---

## Brand & Style
The design system is engineered for high-stakes enterprise management, prioritizing clarity, authority, and executive-level precision. The aesthetic is **Modern Corporate** with a sophisticated **Glassmorphic** influence, utilizing depth and translucency to organize complex data hierarchies.

The target audience consists of senior executives and operations directors who require a UI that feels both reliable and cutting-edge. The interface evokes a sense of "calm control" through heavy use of white space, refined typography, and subtle motion. Visual noise is minimized to ensure that the "Early Warning System" and critical KPIs remain the focal point.

## Colors
This design system utilizes a high-contrast professional palette. The **Deep Navy (#1E3A8A)** serves as the primary anchor for navigation and primary actions, providing a sense of stability. **Soft Executive Blue (#2B6CB0)** is used for interactive elements and secondary emphasis.

In strict adherence to requirements, green is excluded from the system. Success states are handled through the primary blue or neutral icons. The **Early Warning System** utilizes soft pastel ambers and deep ochre tones to signal attention without inducing panic. Surfaces use a layered approach: primary backgrounds are off-white, while functional containers use pure white or translucent glass layers.

## Typography
Typography is the primary tool for information architecture in this design system. **Inter** is used across all levels for its exceptional legibility and neutral, modern character. 

Hierarchy is established through deliberate weight pairing and tight letter-spacing on larger headings to maintain a "dense" executive feel. Labels use increased letter-spacing and uppercase styling for structural metadata. For data-heavy tables and terminal outputs, a monospaced font is introduced to ensure vertical alignment of numerical values.

## Layout & Spacing
The design system follows a **Fixed-Fluid Hybrid** grid. The primary dashboard content is contained within a 1440px max-width, while the side navigation remains fixed. 

A strict 8px spacing scale governs all spatial relationships. On desktop, large 32px or 48px margins create a "gallery" feel for key data widgets. Mobile views transition to a single-column fluid layout with reduced 16px horizontal margins. Grouped elements (like input fields and their labels) use the 4px or 8px increments to maintain proximity.

## Elevation & Depth
Depth is created through **Tonal Layering** and **Ambient Shadows** rather than lines. 
- **Level 0 (Base):** Light grey background (#F8FAFC).
- **Level 1 (Cards):** Pure white surface with a soft, diffused shadow (0px 4px 20px rgba(30, 58, 138, 0.05)).
- **Level 2 (Modals/Overlays):** White surface with a more pronounced shadow and a subtle 1px inner border of 5% opacity primary color.
- **Glass Layers:** For floating navigation or context menus, use a `backdrop-filter: blur(12px)` with a 70% opaque white background. This maintains the "Premium Executive" feel by allowing hints of content to show through.

## Shapes
The shape language is controlled and professional. A standard radius of **8px** is applied to buttons, input fields, and small components. Larger containers and dashboard cards use a **12px** radius to soften the high-density data layouts. This subtle rounding suggests accessibility and modern design without appearing overly "bubbly" or consumer-grade.

## Components
- **Buttons:** Primary buttons use the Deep Navy (#1E3A8A) with a subtle linear gradient toward Soft Blue. Secondary buttons are ghost-style with a 1px border.
- **Input Fields:** Use a light grey fill (#F1F5F9) that transitions to white with a 2px Deep Navy bottom-border on focus.
- **Early Warning Chips:** Soft amber backgrounds (#FEF3C7) with dark amber text (#92400E). Use a pulse micro-interaction for critical alerts.
- **Data Cards:** No outer borders. Use the Level 1 shadow defined in Elevation. Headers within cards should have a subtle 1px divider in #E2E8F0.
- **Progress Indicators:** Since green is disallowed, active progress uses a vibrant blue, and "Completed" states are indicated with a solid Navy checkmark icon.
- **Micro-interactions:** Hover states on interactive cards should include a slight lift (-2px Y-axis) and a deepening of the shadow to provide tactile feedback.
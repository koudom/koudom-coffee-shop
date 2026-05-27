---
name: coffee-shop-ecommerce-design
description: >
    Use this skill when building or designing a Coffee Shop E-commerce Website with login, register, home dashboard, product listing, search, cart, and logout features. Trigger on any request involving coffee shop UI, product cards, auth pages, or storefront layout. This skill defines the full visual system, component rules, UX patterns, and code standards for the project. Always consult this before writing any HTML, CSS, JS, or component code for this project.
---

# Coffee Shop E-Commerce — AI Agent Design Skill

## Project Overview

A simple, beautiful coffee shop storefront. No payment. No admin panel. Just auth + product browsing done well.

**Pages:** Login → Register → Home Dashboard → (Logout returns to Login)

---

## Visual Identity

### Aesthetic Direction

**"Warm Artisan Café"** — Rich, tactile, premium-but-approachable. Think specialty coffee menu board meets modern web.

### Color System

```css
:root {
    /* Core Palette */
    --color-espresso: #1c0a00; /* deepest brown — headers, nav bg */
    --color-roast: #3b1a08; /* dark roast — card overlays, footer */
    --color-caramel: #c07d3a; /* primary accent — buttons, highlights */
    --color-cream: #f5ecd7; /* page background */
    --color-foam: #fdf8ef; /* card background, inputs */
    --color-milk: #ffffff; /* text on dark backgrounds */

    /* Semantic */
    --color-text-primary: #1c0a00;
    --color-text-secondary: #7a5c3e;
    --color-text-muted: #b09070;
    --color-border: #e5d5bc;
    --color-success: #4a8c5c;
    --color-error: #b84040;

    /* Category Tags */
    --tag-hot: #c07d3a; /* Hot Coffee */
    --tag-iced: #4a90b8; /* Iced Coffee */
}
```

### Typography

```css
/* Import in <head> */
@import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500&display=swap");

:root {
    --font-display: "Playfair Display", Georgia, serif; /* headings, logo */
    --font-body: "DM Sans", system-ui, sans-serif; /* body, UI elements */

    --text-xs: 0.75rem;
    --text-sm: 0.875rem;
    --text-base: 1rem;
    --text-lg: 1.125rem;
    --text-xl: 1.25rem;
    --text-2xl: 1.5rem;
    --text-3xl: 2rem;
    --text-4xl: 2.75rem;
}
```

### Spacing & Radius

```css
:root {
    --radius-sm: 6px;
    --radius-md: 12px;
    --radius-lg: 20px;
    --radius-pill: 999px;

    --space-xs: 4px;
    --space-sm: 8px;
    --space-md: 16px;
    --space-lg: 24px;
    --space-xl: 40px;
    --space-2xl: 64px;
}
```

### Shadows

```css
:root {
    --shadow-card: 0 2px 12px rgba(28, 10, 0, 0.08);
    --shadow-hover: 0 8px 28px rgba(28, 10, 0, 0.16);
    --shadow-modal: 0 20px 60px rgba(28, 10, 0, 0.25);
}
```

---

## Component Library

### 1. Navigation Bar

```
[☕ Brew & Bean]          [Search 🔍]     [🛒 2]  [👤 Alex]  [Logout]
```

- Background: `--color-espresso`
- Logo: `--font-display`, white, 1.4rem
- Nav links: `--font-body`, `--color-cream`, 0.9rem
- Cart icon with badge: `--color-caramel` dot
- Profile name: truncate at 14 chars

### 2. Auth Pages (Login & Register)

**Layout:** Centered card, max-width 420px, vertically centered on page.

**Page background:** Full-page coffee texture gradient:

```css
background: linear-gradient(135deg, var(--color-espresso) 0%, var(--color-roast) 60%, #5c2e0a 100%);
```

**Card:**

- Background: `--color-foam`
- Border-radius: `--radius-lg`
- Padding: `--space-xl`
- Shadow: `--shadow-modal`
- Logo/brand name at top center

**Input fields:**

- Border: 1.5px solid `--color-border`
- Focus border: `--color-caramel`
- Radius: `--radius-md`
- Padding: 12px 16px
- Font: `--font-body`
- Label above input, `--text-sm`, `--color-text-secondary`

**Primary button:**

- Background: `--color-caramel`
- Color: white
- Full width
- Radius: `--radius-md`
- Font: `--font-body`, 500 weight
- Hover: darken 10% + `--shadow-hover`
- Transition: 200ms ease

**Link (Login ↔ Register toggle):**

- Color: `--color-caramel`
- Underline on hover

**Validation messages:**

- Error: `--color-error`, `--text-sm`, shown below field
- Success toast: top-right, `--color-success` background

### 3. Product Card

```
┌────────────────────┐
│   [Product Image]  │  ← 240px height, object-cover, border-radius top
│  [🔥 Hot Coffee]   │  ← category tag, bottom-left of image
├────────────────────┤
│  Espresso          │  ← --font-display, --text-xl
│  Bold & intense... │  ← --font-body, --text-sm, 2-line clamp
│  $3.50         [+] │  ← price left, add-to-cart icon button right
└────────────────────┘
```

- Card background: `--color-foam`
- Border: 1px solid `--color-border`
- Border-radius: `--radius-md`
- Shadow: `--shadow-card`
- Hover: `transform: translateY(-4px)`, `--shadow-hover`, transition 250ms
- Category tag: pill shape, small font, colored by type
- Price: `--font-display`, `--color-caramel`, `--text-lg`
- Cart button: round 36px, `--color-caramel` bg on hover

### 4. Search Bar

```
[🔍  Search coffee...]
```

- Max-width: 480px, centered
- Border: 1.5px solid `--color-border`
- Focus: `--color-caramel` border + subtle glow
- Border-radius: `--radius-pill`
- Live filter (no button needed)

### 5. Category Filter

```
[All]  [🔥 Hot Coffee]  [🧊 Iced Coffee]
```

- Pill buttons, row layout
- Inactive: `--color-foam` bg, `--color-text-secondary` text, bordered
- Active: `--color-caramel` bg, white text
- Transition: 150ms

### 6. Cart Icon with Count

```
🛒 ²
```

- SVG icon or unicode, white
- Badge: `--color-caramel`, 18px circle, `--text-xs`, white
- Increment only (no checkout needed)

### 7. About Section

```
┌──────────────────────────────────────────┐
│  ☕ About Brew & Bean                     │
│  We source single-origin beans...         │
│  [small decorative divider]               │
└──────────────────────────────────────────┘
```

- Background: `--color-roast`, text: `--color-cream`
- Full-width, compact (80px–120px height)
- `--font-body` body text, `--font-display` heading
- Place at bottom of dashboard, above footer

---

## Page Layouts

### Login Page

```
[Full dark coffee background]
        ┌────────────┐
        │  ☕ Logo   │
        │  Welcome   │
        │  [Email]   │
        │  [Password]│
        │  [Login]   │
        │ Register → │
        └────────────┘
```

### Register Page

Same layout as Login, with added Name field. After register → auto-redirect to Login with a success message.

### Home Dashboard

```
[Navbar: logo | search | cart | user | logout]
─────────────────────────────────────────────
[Hero greeting: "Good morning, Alex ☕"]
[Category filters: All | Hot | Iced]
─────────────────────────────────────────────
[Product Grid — 3 or 4 columns, responsive]
  [Card] [Card] [Card] [Card]
  [Card] [Card] [Card] [Card]
─────────────────────────────────────────────
[About Section]
```

**Hero greeting:**

- `--font-display`, large text, `--color-espresso`
- Subtext: "What are you craving today?"
- Light beige background strip

---

## Product Data (Use These)

```javascript
const products = [
    {
        id: 1,
        name: "Espresso",
        category: "hot",
        price: 3.5,
        description: "Bold, intense single shot pulled to perfection.",
        image: "https://images.unsplash.com/photo-1510591509098-f4fdc6d0ff04?w=400",
    },
    {
        id: 2,
        name: "Cappuccino",
        category: "hot",
        price: 4.5,
        description: "Espresso topped with velvety steamed milk foam.",
        image: "https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=400",
    },
    {
        id: 3,
        name: "Caramel Latte",
        category: "hot",
        price: 5.0,
        description: "Smooth latte kissed with house-made caramel drizzle.",
        image: "https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=400",
    },
    {
        id: 4,
        name: "Flat White",
        category: "hot",
        price: 4.75,
        description: "Micro-foam milk over a double ristretto. Rich and silky.",
        image: "https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=400",
    },
    {
        id: 5,
        name: "Iced Americano",
        category: "iced",
        price: 4.0,
        description: "Double espresso over ice with cold water. Crisp and clean.",
        image: "https://images.unsplash.com/photo-1517959105821-eaf2591984ca?w=400",
    },
    {
        id: 6,
        name: "Cold Brew",
        category: "iced",
        price: 5.5,
        description: "12-hour slow steep. Naturally sweet and incredibly smooth.",
        image: "https://images.unsplash.com/photo-1541167760496-1628856ab772?w=400",
    },
    {
        id: 7,
        name: "Iced Latte",
        category: "iced",
        price: 5.0,
        description: "Espresso and cold milk over ice. Light and refreshing.",
        image: "https://images.unsplash.com/photo-1511920170033-f8396924c348?w=400",
    },
    {
        id: 8,
        name: "Frappuccino",
        category: "iced",
        price: 6.0,
        description: "Blended coffee, ice, and cream. Sweet, cold perfection.",
        image: "https://images.unsplash.com/photo-1570197788417-0e82375c9371?w=400",
    },
];
```

---

## State & Auth Logic

### localStorage Schema

```javascript
// Users store
localStorage.setItem(
    "cbs_users",
    JSON.stringify([
        { name, email, password }, // password stored plaintext for demo only
    ]),
);

// Session
localStorage.setItem("cbs_session", JSON.stringify({ name, email }));

// Cart
localStorage.setItem("cbs_cart", JSON.stringify([{ productId, quantity }]));
```

### Auth Flow

```
Register → validate fields → save to users[] → redirect to /login
Login    → match email+password → set session → redirect to /dashboard
Logout   → clear session → redirect to /login
Protected → if no session, redirect to /login
```

### Validation Rules

| Field    | Rule                   |
| -------- | ---------------------- |
| Name     | Required, min 2 chars  |
| Email    | Required, valid format |
| Password | Required, min 6 chars  |

---

## Responsive Breakpoints

```css
/* Mobile first */
.product-grid {
    display: grid;
    grid-template-columns: 1fr; /* 1 col — < 480px */
    gap: var(--space-lg);
}

@media (min-width: 480px) {
    .product-grid {
        grid-template-columns: repeat(2, 1fr);
    } /* 2 col */
}

@media (min-width: 768px) {
    .product-grid {
        grid-template-columns: repeat(3, 1fr);
    } /* 3 col */
}

@media (min-width: 1024px) {
    .product-grid {
        grid-template-columns: repeat(4, 1fr);
    } /* 4 col */
}
```

Navbar collapses to hamburger on mobile < 640px.

---

## Animation Rules

Use sparingly. Only these are allowed:

```css
/* Card hover lift */
.product-card {
    transition:
        transform 250ms ease,
        box-shadow 250ms ease;
}
.product-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-hover);
}

/* Button press */
.btn:active {
    transform: scale(0.97);
}

/* Page fade-in */
.page-enter {
    animation: fadeIn 300ms ease forwards;
}
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(8px);
    }
    to {
        opacity: 1;
        transform: none;
    }
}

/* Input focus glow */
input:focus {
    box-shadow: 0 0 0 3px rgba(192, 125, 58, 0.2);
    outline: none;
}
```

No spinning loaders, no bouncing elements, no auto-playing carousels.

---

## Code Standards

### File Structure (if multi-file)

```
coffee-shop/
├── index.html        (login — default landing)
├── register.html
├── dashboard.html
├── assets/
│   └── style.css     (shared styles + design tokens)
└── app.js            (shared logic: auth, products, cart, search)
```

### If single-file HTML

- CSS in `<style>` block
- JS in `<script>` block at end of `<body>`
- All design tokens as CSS variables in `:root`

### JS Conventions

- Use `const`/`let`, no `var`
- Use arrow functions
- No external JS libraries needed
- DOM manipulation via `getElementById` / `querySelector`
- Guard all localStorage reads with try/catch

---

## Quality Checklist

Before finalizing any page, verify:

- [ ] All colors use CSS variables, not hardcoded hex
- [ ] Typography: headings use `--font-display`, body uses `--font-body`
- [ ] Auth: login/register validation with visible error messages
- [ ] Search filters product list live as user types
- [ ] Category filter works with search (both active simultaneously)
- [ ] Cart count updates when user clicks add button
- [ ] Logout clears session and redirects to login
- [ ] Protected pages redirect unauthenticated users to login
- [ ] Responsive: usable on 375px wide screen
- [ ] No placeholder lorem ipsum text in final output
- [ ] Product images use the Unsplash URLs from this skill

---

## Common Mistakes to Avoid

❌ Don't use `alert()` for validation — use inline error messages  
❌ Don't use generic system fonts  
❌ Don't hardcode user data — always use localStorage  
❌ Don't skip input validation on register  
❌ Don't add payment, order tracking, or admin features  
❌ Don't make the search require a button click — it should be live  
❌ Don't forget to show the logged-in user's name in the navbar

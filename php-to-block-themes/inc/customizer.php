{
  "$schema": "https://schemas.wp.org/wp/6.7/theme.json",
  "version": 3,
  "settings": {
    "appearanceTools": true,
    "layout": {
      "contentSize": "800px",
      "wideSize": "1200px"
    },
    "color": {
      "custom": true,
      "palette": [
        {
          "slug": "primary",
          "color": "#0073aa",
          "name": "Primary"
        },
        {
          "slug": "secondary",
          "color": "#005177",
          "name": "Secondary"
        },
        {
          "slug": "background",
          "color": "#ffffff",
          "name": "Background"
        },
        {
          "slug": "foreground",
          "color": "#333333",
          "name": "Foreground"
        },
        {
          "slug": "header-background",
          "color": "#ffffff",
          "name": "Header Background"
        },
        {
          "slug": "link",
          "color": "#0073aa",
          "name": "Link"
        },
        {
          "slug": "button-background",
          "color": "#0073aa",
          "name": "Button Background"
        },
        {
          "slug": "button-text",
          "color": "#ffffff",
          "name": "Button Text"
        }
      ]
    },
    "typography": {
      "custom": true,
      "fontFamilies": [
        {
          "fontFamily": "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif",
          "slug": "system",
          "name": "System Font"
        },
        {
          "fontFamily": "Georgia, 'Times New Roman', Times, serif",
          "slug": "serif",
          "name": "Serif"
        },
        {
          "fontFamily": "'Courier New', Courier, monospace",
          "slug": "monospace",
          "name": "Monospace"
        }
      ],
      "fontSizes": [
        {
          "slug": "small",
          "size": "0.875rem",
          "name": "Small"
        },
        {
          "slug": "medium",
          "size": "1rem",
          "name": "Medium"
        },
        {
          "slug": "large",
          "size": "1.5rem",
          "name": "Large"
        },
        {
          "slug": "x-large",
          "size": "2rem",
          "name": "Extra Large"
        }
      ]
    },
    "spacing": {
      "padding": true,
      "margin": true,
      "units": ["px", "em", "rem", "vh", "vw", "%"]
    }
  },
  "styles": {
    "color": {
      "background": "#ffffff",
      "text": "#333333"
    },
    "typography": {
      "fontFamily": "var(--wp--preset--font-family--system)",
      "fontSize": "1rem",
      "lineHeight": "1.6"
    },
    "spacing": {
      "padding": {
        "top": "0",
        "right": "var(--wp--preset--spacing--50)",
        "bottom": "0",
        "left": "var(--wp--preset--spacing--50)"
      }
    },
    "elements": {
      "link": {
        "color": {
          "text": "var(--wp--preset--color--link)"
        },
        ":hover": {
          "color": {
            "text": "var(--wp--preset--color--secondary)"
          }
        }
      },
      "button": {
        "color": {
          "background": "var(--wp--preset--color--button-background)",
          "text": "var(--wp--preset--color--button-text)"
        },
        "typography": {
          "fontSize": "1rem",
          "fontWeight": "600"
        },
        "spacing": {
          "padding": {
            "top": "0.75em",
            "right": "1.5em",
            "bottom": "0.75em",
            "left": "1.5em"
          }
        },
        ":hover": {
          "color": {
            "background": "var(--wp--preset--color--secondary)",
            "text": "var(--wp--preset--color--button-text)"
          }
        }
      },
      "heading": {
        "typography": {
          "fontFamily": "var(--wp--preset--font-family--system)",
          "fontWeight": "700",
          "lineHeight": "1.3"
        },
        "color": {
          "text": "var(--wp--preset--color--foreground)"
        }
      },
      "h1": {
        "typography": {
          "fontSize": "2.5rem"
        }
      },
      "h2": {
        "typography": {
          "fontSize": "2rem"
        }
      },
      "h3": {
        "typography": {
          "fontSize": "1.75rem"
        }
      },
      "h4": {
        "typography": {
          "fontSize": "1.5rem"
        }
      },
      "h5": {
        "typography": {
          "fontSize": "1.25rem"
        }
      },
      "h6": {
        "typography": {
          "fontSize": "1rem"
        }
      }
    },
    "blocks": {
      "core/group": {
        "color": {
          "background": "var(--wp--preset--color--background)",
          "text": "var(--wp--preset--color--foreground)"
        }
      },
      "core/template-part": {
        "variations": {
          "header": {
            "color": {
              "background": "var(--wp--preset--color--header-background)"
            }
          }
        }
      },
      "core/site-title": {
        "typography": {
          "fontWeight": "700"
        }
      },
      "core/navigation": {
        "typography": {
          "fontFamily": "var(--wp--preset--font-family--system)",
          "fontSize": "1rem"
        }
      },
      "core/post-title": {
        "typography": {
          "fontFamily": "var(--wp--preset--font-family--system)",
          "fontWeight": "700"
        }
      }
    }
  },
  "templateParts": [
    {
      "name": "header",
      "title": "Header",
      "area": "header"
    },
    {
      "name": "footer",
      "title": "Footer",
      "area": "footer"
    },
    {
      "name": "sidebar",
      "title": "Sidebar",
      "area": "uncategorized"
    }
  ],
  "customTemplates": [
    {
      "name": "page-no-title",
      "title": "Page (No Title)",
      "postTypes": ["page"]
    },
    {
      "name": "blank",
      "title": "Blank",
      "postTypes": ["page", "post"]
    }
  ]
}
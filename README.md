# Post Highlight Block

Bloco Gutenberg para exibir posts em destaque (1 post grande + posts pequenos).

## 📦 Instalação

1. Baixe este repositório como `.zip` e extraia na pasta `wp-content/plugins/`.
2. Ative o plugin no painel do WordPress.

## 🛠 Desenvolvimento

Este projeto usa [@wordpress/scripts](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/) para empacotar o JavaScript com ESNext.

### Passos para rodar localmente:

```bash
cd post-highlight
npm install
npm run start
```

- `npm run start` compila em modo de desenvolvimento com _hot reload_.
- `npm run build` gera a versão otimizada para produção.

## 🌍 Tradução (i18n)

Para gerar o arquivo de tradução `.pot`:

```bash
npx @wordpress/scripts i18n make-pot . languages/post-highlight.pot
```

Depois, use ferramentas como Poedit ou Loco Translate para criar arquivos `.po/.mo`.

## ⚙️ Estrutura

```
post-highlight/
├── block.json
├── post-highlight.php
├── render.php
├── languages/
│   └── post-highlight.pot
├── src/
│   ├── index.js
│   └── edit.js
├── style.css
├── editor.css
├── package.json
└── README.md
```

## 📤 Subindo para o GitHub

```bash
git init
git add .
git commit -m "feat: initial commit with Gutenberg block"
git branch -M main
git remote add origin https://github.com/SEU-USUARIO/post-highlight.git
git push -u origin main
```

Adicione também um `.gitignore`:

```gitignore
/node_modules/
/build/
.DS_Store
*.zip
```

---

✍️ **Dica:** Para personalizar o visual, edite o `style.css` e adicione suas próprias classes ou use TailwindCSS/SASS.

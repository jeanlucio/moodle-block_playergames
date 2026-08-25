# Moodle Block PlayerGames

![Moodle](https://img.shields.io/badge/Moodle-4.5%2B-orange?style=flat&logo=moodle&logoColor=white)
![License](https://img.shields.io/badge/License-GPLv3-blue?style=flat)
![Status](https://img.shields.io/badge/Status-Alpha-red?style=flat)
[![Latest Release](https://img.shields.io/github/v/release/jeanlucio/moodle-block_playergames?style=flat)](https://github.com/jeanlucio/moodle-block_playergames/releases)
[![PlayerGames Ecosystem](https://img.shields.io/badge/PlayerGames-Ecosystem-6f42c1?style=flat&logo=gamepad&logoColor=white)](https://jeanlucio.github.io/playergames/)
![Role](https://img.shields.io/badge/Role-Companion_Block-0d6efd?style=flat)
[![Author](https://img.shields.io/badge/by-Jean_Lucio-6f42c1?style=flat)](https://github.com/jeanlucio/)

[![Moodle Plugin CI](https://github.com/jeanlucio/moodle-block_playergames/actions/workflows/ci.yml/badge.svg)](https://github.com/jeanlucio/moodle-block_playergames/actions/workflows/ci.yml)
[![Last Commit](https://img.shields.io/github/last-commit/jeanlucio/moodle-block_playergames?style=flat)](https://github.com/jeanlucio/moodle-block_playergames/commits)
[![Open Issues](https://img.shields.io/github/issues/jeanlucio/moodle-block_playergames?style=flat)](https://github.com/jeanlucio/moodle-block_playergames/issues)

> ⚠️ **This plugin is under active development.** It is not yet published on the Moodle Plugin Directory.

[English](#english) | [Português](#português)

---

## English

The **PlayerGames Block** is a thin sidebar widget for the **PlayerGames** gamification ecosystem.
It mirrors a user's site-wide Player Hub profile — avatar, level, season XP, streak, and rankings
— wherever a site administrator adds it, without holding any data or business logic of its own.

Everything the widget shows and does is delegated to **`local_playergames`**, which this block
depends on directly.

📚 **[Full documentation](https://jeanlucio.github.io/moodle-block_playergames/)** — features,
the three widget states, avatar modal, ecosystem overview, the full 11-case test suite, and
security & privacy details.

### 📦 Requirements

| Component | Version |
|-----------|---------|
| Moodle    | 4.5+    |
| PHP       | 8.1+    |
| `local_playergames` | Required (hard dependency) |

### 🛠️ Installation

> ⚠️ This plugin is not yet published on the Moodle Plugin Directory. Install manually from this repository.

1. Install **`local_playergames`** first — this block will not install without it.
2. Download the `.zip` file or clone this repository.
3. Extract the folder into your Moodle `blocks/` directory.
4. Rename the folder to `playergames` (if necessary).
   Final path: `your-moodle/blocks/playergames/`
5. Visit **Site administration → Notifications** to complete installation.

### 🆘 Support

Found a bug or have a question? Open an issue on the
[issue tracker](https://github.com/jeanlucio/moodle-block_playergames/issues).

### 📄 License

This project is licensed under the **GNU General Public License v3 (GPLv3)**.

**Copyright:** 2026 Jean Lúcio

### 👤 Maintainer

Maintained by [Jean Lúcio](https://github.com/jeanlucio).

[⬆️ Back to top](#english)

---

## Português

> ⚠️ **Este plugin está em desenvolvimento ativo.** Ainda não foi publicado no Diretório de Plugins do Moodle.

O **Bloco PlayerGames** é um widget compacto de sidebar para o ecossistema de gamificação
**PlayerGames**. Ele espelha o perfil site-wide do Player Hub de um usuário — avatar, nível, XP de
temporada, sequência e rankings — onde quer que um administrador do site o adicione, sem guardar
nenhum dado ou lógica de negócio próprios.

Tudo que o widget mostra e faz é delegado ao **`local_playergames`**, do qual este bloco depende
diretamente.

📚 **[Documentação completa](https://jeanlucio.github.io/moodle-block_playergames/pt.html)** —
funcionalidades, os três estados do widget, modal de avatar, visão geral do ecossistema, a suíte
completa de 11 testes, e detalhes de segurança e privacidade.

### 📦 Requisitos

| Componente | Versão |
|------------|--------|
| Moodle     | 4.5+   |
| PHP        | 8.1+   |
| `local_playergames` | Obrigatório (dependência rígida) |

### 🛠️ Instalação

> ⚠️ Este plugin ainda não está publicado no Diretório de Plugins do Moodle. Instale manualmente a partir deste repositório.

1. Instale o **`local_playergames`** primeiro — este bloco não instala sem ele.
2. Baixe o arquivo `.zip` ou clone este repositório.
3. Extraia na pasta `blocks/` do seu Moodle.
4. Renomeie para `playergames` (se necessário).
   Caminho final: `seu-moodle/blocks/playergames/`
5. Acesse **Administração do site → Notificações** para concluir a instalação.

### 🆘 Suporte

Encontrou um bug ou tem alguma dúvida? Abra uma issue no
[rastreador de issues](https://github.com/jeanlucio/moodle-block_playergames/issues).

### 📄 Licença

Este projeto é licenciado sob a **GNU General Public License v3 (GPLv3)**.

**Copyright:** 2026 Jean Lúcio

### 👤 Mantenedor

Mantido por [Jean Lúcio](https://github.com/jeanlucio).

[⬆️ Voltar ao topo](#português)

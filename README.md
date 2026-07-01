# Moodle Block PlayerGames

[![Moodle Plugin CI](https://github.com/jeanlucio/moodle-block_playergames/actions/workflows/ci.yml/badge.svg)](https://github.com/jeanlucio/moodle-block_playergames/actions/workflows/ci.yml)
![Moodle](https://img.shields.io/badge/Moodle-4.5%2B-orange?style=flat-square&logo=moodle&logoColor=white)
![License](https://img.shields.io/badge/License-GPLv3-blue?style=flat-square)
![Status](https://img.shields.io/badge/Status-Alpha-yellow?style=flat-square)
[![PlayerGames Ecosystem](https://img.shields.io/badge/PlayerGames-Ecosystem-6f42c1?style=flat-square&logo=gamepad&logoColor=white)](https://moodle.org/plugins/browse.php?list=contributor&id=3970322)
![Role](https://img.shields.io/badge/Role-Companion_Block-0d6efd?style=flat-square)

[English](#english) | [Português](#português)

<details>
<summary><b>📑 Table of Contents</b></summary>

- [✨ Features](#-features)
- [🕹️ PlayerGames Ecosystem](#-playergames-ecosystem)
- [📦 Requirements](#-requirements)
- [🛠️ Installation](#-installation)
- [📖 Usage](#-usage)
- [🧪 Automated Tests](#-automated-tests)
- [🔐 Security & Privacy](#-security--privacy)
- [📄 License / Licença](#-license--licença)

</details>

---

## English

The **PlayerGames Block** is a thin sidebar widget for the **PlayerGames** gamification ecosystem. It mirrors a user's site-wide Player Hub profile — avatar, level, season XP, streak, and rankings — wherever a site administrator adds it, without holding any data or business logic of its own.

Everything the widget shows and does is delegated to **`local_playergames`**, which this block depends on directly. If you are looking for the full experience (missions, avatar collection, complete rankings), that lives in the Player Hub (`local_playergames`) itself; this block is a compact pointer to it.

---

### ✨ Features

* 🦊 **Avatar at a glance:** shows the equipped avatar; clicking it opens the same avatar-collection modal used by the Player Hub, so equipping there or here stays in sync.
* 📊 **Season progress:** level, XP and a progress bar toward the next level.
* 🎮 **Today's games:** shortcuts to the day's available minigames, with done/pending/locked state.
* 🔥 **Streak, freezes & check-ins:** compact stats with accessible labels.
* 📘 **Learning XP (optional):** shows the XP mirrored from course activity via `block_playerhud`, when that bridge and the admin setting are both active — student-only, never mixed with season XP.
* 🏆 **Ranking position + one-click opt-in:** season and learning ranking positions, each with its own toggle to appear or not — no need to open the full Hub.
* ❓ **Built-in help:** a short modal explaining the two XP pools, avatars and rankings — the same content shown on every Player Hub page.
* 🕐 **Activity history shortcut:** a direct link to the full log of XP, streak and freeze events.
* 🔌 **Three states, no logic of its own:** *normal* (active player), *paused* (the user opted out of gamification — with a link to reactivate), or *hidden* (no active season, or the viewer is excluded by the site's participant-group setting). All three states are computed by `local_playergames`; the block only picks which template to render.

---

### 🕹️ PlayerGames Ecosystem

PlayerGames Block is a companion to **`local_playergames`**, the ecosystem's central hub, and is designed to complement — not overlap with — **`block_playerhud`**:

| | Scope | Where it appears |
|---|---|---|
| **`block_playerhud`** | Per-course XP, items, quests, story | Inside a course |
| **`block_playergames`** (this plugin) | Site-wide season XP, streak, rankings | Site front page and Dashboard, **not** inside courses |

* **PlayerGames Hub (`local_playergames`):** the required dependency — hub page, rankings, missions, avatar catalog, activity log.
  👉 https://github.com/jeanlucio/moodle-local_playergames

* **PlayerHUD:** per-course gamification block. Its course XP can optionally mirror into this ecosystem's "Learning XP" pool.
  👉 https://github.com/jeanlucio/moodle-block_playerhud

---

### 📦 Requirements

| Component | Version |
|-----------|---------|
| Moodle    | 4.5+    |
| PHP       | 8.1+    |
| `local_playergames` | Required (hard dependency) |

---

### 🛠️ Installation

1. Install **`local_playergames`** first — this block will not install without it.
2. Download the `.zip` file or clone this repository.
3. Extract the folder into your Moodle `blocks/` directory.
4. Rename the folder to `playergames` (if necessary).
   Final path:
   `your-moodle/blocks/playergames/`
5. Visit **Site administration > Notifications** to complete installation.

---

### 📖 Usage

1. Turn on **editing mode**.
2. Go to the **site front page** and/or your **Dashboard**, and use **"Add a block" → PlayerGames**.
3. Click the avatar to equip a different one, the ❓ icon for a quick explanation, or the 🕐 icon for the full activity history.

> 💡 **Recommendation for site administrators:** this block is designed for the **site front page and the Dashboard**, not for individual courses (that role belongs to `block_playerhud`). For the widget to become visible automatically to everyone:
> - Add it to the **site front page** while editing — the front page is shared by the whole site, so this alone makes it visible to every user with a single action.
> - Add it to the **default Dashboard page** (*Site administration → Appearance → Default Dashboard page*) so new dashboards start with it. Existing users who already customised their own Dashboard will not get it retroactively unless you also use *"Reset Dashboard for all users"* — a destructive action that resets everyone's personal Dashboard layout, so use it deliberately.

---

### 🧪 Automated Tests

The only business logic that belongs to this plugin — which of the three states to render — is covered by PHPUnit. Everything else is orchestration of `local_playergames` managers, already tested in that plugin.

#### PHPUnit — Unit & Integration Tests

| Test file | Cases | What is covered |
|-----------|------:|----------------|
| `output/widget_test.php` | 8 | State selection: paused when gamification is opted out, hidden with no active season or when excluded by the participant-group setting, normal otherwise; normal-state data sanity (username, level, XP); learning XP hidden when the admin setting is off; self and learning ranking position + opt-in toggle text once the user opts into each ranking |
| `privacy/provider_test.php` | 2 | Implements `null_provider`; `get_reason()` points to a real lang string |
| **Total** | **10** | |

**Line coverage by class (PHPUnit + Xdebug):**

| Class | Line coverage |
|-------|:-------------:|
| `output\widget` | 100% |
| `privacy\provider` | 100% |
| **Overall** | **100%** |

```bash
vendor/bin/phpunit --testsuite block_playergames
```

---

### 🔐 Security & Privacy

* Capability-based access control (`block/playergames:view`, granted to every authenticated user, since the widget lives on shared, site-wide pages rather than inside a course).
* No data of its own: the Privacy API provider is a `null_provider` — all personal data (profile, XP, streak, activity log) is owned and declared by `local_playergames`.
* All write actions (equip avatar, toggle ranking visibility) go through the web services already validated by `local_playergames`; this block performs no writes of its own.

---

## 📄 License / Licença

This project is licensed under the **GNU General Public License v3 (GPLv3)**.

**Copyright:** 2026 Jean Lúcio

---

## Português

O **Bloco PlayerGames** é um widget compacto de sidebar para o ecossistema de gamificação **PlayerGames**. Ele espelha o perfil site-wide do Player Hub de um usuário — avatar, nível, XP de temporada, sequência e rankings — onde quer que um administrador do site o adicione, sem guardar nenhum dado ou lógica de negócio próprios.

Tudo que o widget mostra e faz é delegado ao **`local_playergames`**, do qual este bloco depende diretamente. Se você procura a experiência completa (missões, coleção de avatares, rankings completos), ela está no próprio Player Hub (`local_playergames`); este bloco é um ponteiro compacto para ele.

<details>
<summary><b>📑 Índice</b></summary>

- [✨ Funcionalidades](#-funcionalidades)
- [🕹️ Ecossistema PlayerGames](#-ecossistema-playergames)
- [📦 Requisitos](#-requisitos)
- [🛠️ Instalação](#-instalação)
- [📖 Como Usar](#-como-usar)
- [🧪 Testes Automatizados](#-testes-automatizados)
- [🔐 Segurança e Privacidade](#-segurança-e-privacidade)
- [📄 Licença](#-licença)

</details>

---

### ✨ Funcionalidades

* 🦊 **Avatar em destaque:** mostra o avatar equipado; clicar nele abre o mesmo modal de coleção usado no Player Hub, então equipar em um lugar reflete no outro.
* 📊 **Progresso de temporada:** nível, XP e barra de progresso até o próximo nível.
* 🎮 **Jogos de hoje:** atalhos para os minijogos disponíveis no dia, com estado feito/pendente/bloqueado.
* 🔥 **Sequência, congelamentos e check-ins:** estatísticas compactas com rótulos acessíveis.
* 📘 **XP de aprendizado (opcional):** mostra o XP espelhado da atividade em cursos via `block_playerhud`, quando essa ponte e a configuração do admin estão ativas — exclusivo de estudante, nunca somado ao XP de temporada.
* 🏆 **Posição no ranking + opt-in em um clique:** posições nos rankings de temporada e de aprendizado, cada um com seu próprio toggle para aparecer ou não — sem precisar abrir o Hub completo.
* ❓ **Ajuda embutida:** um modal curto explicando os dois pools de XP, avatares e rankings — o mesmo conteúdo exibido em toda página do Player Hub.
* 🕐 **Atalho para o histórico:** link direto para o log completo de eventos de XP, sequência e congelamento.
* 🔌 **Três estados, nenhuma lógica própria:** *normal* (jogador ativo), *pausado* (o usuário optou por sair da gamificação — com link para reativar), ou *oculto* (sem temporada ativa, ou o usuário é excluído pela configuração de grupo de participantes do site). Os três estados são calculados pelo `local_playergames`; o bloco só escolhe qual template renderizar.

---

### 🕹️ Ecossistema PlayerGames

O Bloco PlayerGames é um companheiro do **`local_playergames`**, o hub central do ecossistema, projetado para complementar — não sobrepor — o **`block_playerhud`**:

| | Escopo | Onde aparece |
|---|---|---|
| **`block_playerhud`** | XP, itens, missões e história por curso | Dentro de um curso |
| **`block_playergames`** (este plugin) | XP de temporada, sequência e rankings site-wide | Página inicial do site e Painel, **não** dentro de cursos |

* **PlayerGames Hub (`local_playergames`):** a dependência obrigatória — página do hub, rankings, missões, catálogo de avatares, log de atividade.
  👉 https://github.com/jeanlucio/moodle-local_playergames

* **PlayerHUD:** bloco de gamificação por curso. Seu XP de curso pode opcionalmente espelhar para o pool "XP de aprendizado" deste ecossistema.
  👉 https://github.com/jeanlucio/moodle-block_playerhud

---

### 📦 Requisitos

| Componente | Versão |
|------------|--------|
| Moodle     | 4.5+   |
| PHP        | 8.1+   |
| `local_playergames` | Obrigatório (dependência rígida) |

---

### 🛠️ Instalação

1. Instale o **`local_playergames`** primeiro — este bloco não instala sem ele.
2. Baixe o arquivo `.zip` ou clone este repositório.
3. Extraia na pasta `blocks/` do seu Moodle.
4. Renomeie para `playergames` (se necessário).
   Caminho final:
   `seu-moodle/blocks/playergames/`
5. Acesse **Administração do site > Notificações** para concluir a instalação.

---

### 📖 Como Usar

1. Ative o **modo de edição**.
2. Vá até a **página inicial do site** e/ou o seu **Painel**, e use **"Adicionar um bloco" → PlayerGames**.
3. Clique no avatar para trocar, no ícone ❓ para uma explicação rápida, ou no ícone 🕐 para o histórico completo de atividade.

> 💡 **Recomendação para administradores do site:** este bloco foi pensado para a **página inicial do site e o Painel**, não para cursos individuais (esse papel é do `block_playerhud`). Para que o widget fique visível automaticamente para todos:
> - Adicione-o na **página inicial do site** em modo de edição — como a página inicial é compartilhada por todo o site, essa única ação já torna o bloco visível para qualquer usuário.
> - Adicione-o também na **página de Painel padrão** (*Administração do site → Aparência → Página do Painel padrão*), para que novos painéis já comecem com ele. Usuários que já personalizaram o próprio Painel não recebem isso retroativamente, a menos que você também use *"Redefinir Painel para todos os usuários"* — uma ação destrutiva que reseta o layout pessoal de todo mundo, então use com critério.

---

### 🧪 Testes Automatizados

A única lógica de negócio deste plugin — qual dos três estados renderizar — é coberta por PHPUnit. Todo o resto é orquestração de managers do `local_playergames`, já testados naquele plugin.

#### PHPUnit — Testes Unitários e de Integração

| Arquivo de teste | Casos | O que é coberto |
|------------------|------:|----------------|
| `output/widget_test.php` | 8 | Seleção de estado: pausado quando a gamificação está desativada, oculto sem temporada ativa ou quando excluído pela configuração de grupo de participantes, normal nos demais casos; sanidade dos dados do estado normal (nome, nível, XP); XP de aprendizado oculto quando a configuração do admin está desligada; posição e texto do toggle de opt-in nos rankings de temporada e de aprendizado quando o usuário adere a cada um |
| `privacy/provider_test.php` | 2 | Implementa `null_provider`; `get_reason()` aponta para uma string de idioma real |
| **Total** | **10** | |

**Cobertura de linhas por classe (PHPUnit + Xdebug):**

| Classe | Cobertura de linhas |
|--------|:-------------------:|
| `output\widget` | 100% |
| `privacy\provider` | 100% |
| **Total** | **100%** |

```bash
vendor/bin/phpunit --testsuite block_playergames
```

---

### 🔐 Segurança e Privacidade

* Controle de acesso baseado em capability (`block/playergames:view`, concedida a todo usuário autenticado, já que o widget vive em páginas compartilhadas e site-wide, não dentro de um curso).
* Nenhum dado próprio: o provedor da Privacy API é um `null_provider` — todo dado pessoal (perfil, XP, sequência, log de atividade) é de propriedade e declarado pelo `local_playergames`.
* Toda ação de escrita (equipar avatar, alternar visibilidade no ranking) passa pelos web services já validados pelo `local_playergames`; este bloco não realiza escritas próprias.

---

## 📄 Licença

Este projeto é licenciado sob a **GNU General Public License v3 (GPLv3)**.

**Copyright:** 2026 Jean Lúcio

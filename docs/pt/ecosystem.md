# 🕹️ Ecossistema PlayerGames

O Bloco PlayerGames é um companheiro do **`local_playergames`**, o hub central do ecossistema,
projetado para complementar — não sobrepor — o **`block_playerhud`**:

| | Escopo | Onde aparece |
|---|---|---|
| **`block_playerhud`** | XP, itens, missões e história por curso | Dentro de um curso |
| **`block_playergames`** (este plugin) | XP de temporada, sequência e rankings site-wide | Página inicial do site e Painel, **não** dentro de cursos |

* **PlayerGames Hub (`local_playergames`):** a dependência obrigatória — página do hub, rankings,
  missões, catálogo de avatares, log de atividade.
  👉 [github.com/jeanlucio/moodle-local_playergames](https://github.com/jeanlucio/moodle-local_playergames)

* **Bloco PlayerHUD (`block_playerhud`):** bloco de gamificação por curso. Seu XP de curso pode
  opcionalmente espelhar para o pool "XP de aprendizado" deste ecossistema.
  👉 [github.com/jeanlucio/moodle-block_playerhud](https://github.com/jeanlucio/moodle-block_playerhud)

* **AI Hub (`local_aihub`):** broker BYOK compartilhado para geração de cartuchos com IA em
  `local_playergames`. Opcional.
  👉 [github.com/jeanlucio/moodle-local_aihub](https://github.com/jeanlucio/moodle-local_aihub)

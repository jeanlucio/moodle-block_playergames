# 🔐 Segurança e Privacidade

## Controle de Acesso

Controle de acesso baseado em capability: `block/playergames:view` é concedida a todo usuário
autenticado, já que o widget vive em páginas compartilhadas e site-wide, não dentro de um curso.

## Privacy API

Este bloco implementa a interface `null_provider` — ele **não guarda nenhum dado pessoal próprio**.
Todos os dados pessoais (perfil, XP, sequência, log de atividade, avatar) são de propriedade e
declarados pelo `local_playergames`.

## Operações de Escrita

Toda ação de escrita — equipar um avatar, alternar visibilidade no ranking — passa pelos web
services já validados e protegidos pelo `local_playergames`. Este bloco não realiza escritas
próprias.

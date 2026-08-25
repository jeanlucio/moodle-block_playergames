# ✨ Funcionalidades

* 🦊 **Avatar em destaque:** mostra o avatar equipado; clicar nele abre o mesmo modal de coleção
  usado no Player Hub, então equipar em um lugar reflete no outro.
* 📊 **Progresso de temporada:** nível, XP e barra de progresso até o próximo nível.
* 🎮 **Jogos de hoje:** atalhos para os minijogos disponíveis no dia, com estado
  feito/pendente/bloqueado.
* 🔥 **Sequência, congelamentos e check-ins:** estatísticas compactas com rótulos acessíveis.
* 📘 **XP de aprendizado (opcional):** mostra o XP espelhado da atividade em cursos via
  `block_playerhud`, quando essa ponte e a configuração do admin estão ativas — exclusivo de
  estudante, nunca somado ao XP de temporada.
* 🏆 **Posição no ranking + opt-in em um clique:** posições nos rankings de temporada e de
  aprendizado, cada um com seu próprio toggle para aparecer ou não — sem precisar abrir o Hub
  completo.
* ❓ **Ajuda embutida:** um modal curto explicando os dois pools de XP, avatares e rankings — o
  mesmo conteúdo exibido em toda página do Player Hub.
* 🕐 **Atalho para o histórico:** link direto para o log completo de eventos de XP, sequência e
  congelamento.
* 🔌 **Três estados, nenhuma lógica própria:**
  * *Normal* — jogador ativo: avatar, nível, barra de XP, jogos do dia, sequência e rankings
    visíveis.
  * *Pausado* — o usuário optou por sair da gamificação, com link para reativar diretamente do
    bloco.
  * *Oculto* — sem temporada ativa, ou o usuário é excluído pela configuração de grupo de
    participantes do site.

  Os três estados são calculados pelo `local_playergames`; o bloco só escolhe qual template
  renderizar.

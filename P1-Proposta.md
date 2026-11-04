# 1. API Escolhida e Endpoints a Utilizar

* **API Selecionada:** API Laravel de Adoção de Animais (desenvolvida no semestre anterior), com autenticação baseada em tokens (Laravel Sanctum), controlo de acessos por papéis (RBAC) e respostas JSON padronizadas (`ApiResponse`).

## Endpoints Principais que a Aplicação vai Consumir

* **Públicos (Sem autenticação):** `GET /api/animals`, `GET /api/animals/{id}`, `POST /api/register`, `POST /api/login`
* **Autenticados (Adotante):** `GET /api/me`, `POST /api/applications`, `GET /api/my-applications`, `POST /api/refresh`, `POST /api/logout`
* **Autenticados (Staff):** `POST /api/register-staff`, `POST /api/animals`, `PATCH /api/animals/{id}`, `DELETE /api/animals/{id}`, `GET /api/applications`, `GET /api/animals/{id}/applications`, `PATCH /api/applications/{id}/status`, `GET /api/reports/adoptions`

# 2. Lista de Funcionalidades por Requisitos

* **R1 & R6 — Página de Apresentação (HTML, CSS e Acessibilidade):**
  * **Página estática responsiva:** Desenvolvida em HTML semântico e CSS próprio (com Flexbox e Grid) em formato mobile-first.
  * **Acessibilidade por Teclado:** Garantia de foco visual claro (`outline`) e navegação lógica por tecla `Tab` nos elementos interativos.
  * **Contraste de Cores WCAG:** Validação visual e de contraste para atingir uma pontuação > 90 no Google Lighthouse.

* **R2 — Multimédia:**
  * **Vídeo Institucional / Pós-Adoção:** Inclusão de um vídeo de testemunho de um adotante com o seu novo animal, acompanhado de legendas sincronizadas em formato `WebVTT`.

* **R3 — JavaScript sem Framework:**
  * **Quiz / Formulário de Pré-Avaliação:** Questionário interativo em JavaScript puro (com manipulação do DOM e gestão de eventos) para avaliar a aptidão do adotante antes da submissão.

* **R4 — Aplicação React (SPA, Componentes e Rotas):**
  * **Listagem e Filtragem de Animais:** Componente de grelha reutilizável (`AnimalCard`) com filtros dinâmicos por espécie, tamanho e estado.
  * **Página de Detalhe:** Ficha completa e individualizada de cada animal.
  * **Formulários de Utilizador:** Ecrãs dedicados ao registo, login e submissão de candidaturas.

* **R5 — Integração com a API (Leitura, Escrita e Autenticação):**
  * **Operações de Leitura (GET):** Consulta de animais, perfil de utilizador, histórico pessoal de candidaturas e relatórios estatísticos do staff.
  * **Operações de Escrita (POST, PATCH, DELETE):** Registo de utilizadores, submissão de candidaturas, criação/atualização/remoção lógica de animais (Soft Delete) e alteração de estados de candidaturas.
  * **Autenticação por Token:** Gestão de sessões seguras através do Laravel Sanctum (`Bearer Token`) com separação de papéis (Adotante vs. Staff).

* **R7 & R8 — Publicação e Documentação:**
  * **Deploy Online:** Publicação da aplicação web numa plataforma de alojamento (Vercel, Netlify ou GitHub Pages).
  * **Controle e Documentação:** Repositório Git com commits regulares e partilhados, acompanhado pelo `README.md` estruturado e pelo ficheiro `REGISTO_IA.md` completo.



# IDEIAS ADICIONAIS

## 1. Funcionalidades Extras para o Utilizador / Adotante (SPA React)

* Favoritos / Lista de Desejos (Wishlist) Local:
Permitir que os visitantes ou adotantes marquem determinados animais com um "gosto" (ícone de coração) para guardarem os preferidos numa aba dedicada (usando o localStorage do browser), facilitando a comparação antes de escolherem qual adotar.

* Sistema de Notificações / Alertas visuais (Toasts):
Em vez de alertas básicos do browser (alert()), implementar feedback visual elegante no canto do ecrã (tipo Toast notifications) quando uma candidatura é submetida com sucesso, quando o login falha ou quando os dados do perfil são atualizados.

* Filtros Avançados e Ordenação Combinada:
Além dos filtros básicos por espécie/tamanho, adicionar ordenação dinâmica na grelha (por exemplo, ordenar por idade do animal: mais novo para mais velho, ou por ordem alfabética).

* Partilha Social de Fichas de Animais:
Um botão na página de detalhe do animal que copia o link direto para a área de transferência ou permite partilhar o perfil do animal nas redes sociais.

## 2. Funcionalidades Extras para o Staff / Gestão (SPA React)

* *Dashboard Analítico com Gráficos / Indicadores Visuais:
Utilizar os dados do endpoint de relatórios (/api/reports/adoptions) para construir gráficos simples ou cartões de resumo destacados no painel do staff (ex: total de animais disponíveis, taxa de adoção bem-sucedida, total de candidaturas pendentes a aguardar aprovação).*

* Pré-visualização de Imagens por URL:
No formulário de registo/edição de animais do staff, adicionar uma pequena pré-visualização dinâmica da imagem do animal à medida que o utilizador cola o link da foto no campo de texto.

* *Modo de Ecrã / Tabela com Pesquisa Rápida (Staff):
Na listagem de candidaturas do staff, adicionar uma barra de pesquisa rápida por nome do adotante para encontrar processos de forma instantânea sem precisar de folhear as páginas todas.*

## 3. Funcionalidades Extras para a Página de Apresentação (HTML/CSS puro)

* Contador de Impacto Animado (Estatísticas do Abrigo):
Uma secção na página estática com números em destaque (ex: "+500 Animais Adotados", "+10 Anos de Atividade").

* *Secção de Testemunhos / Histórias de Sucesso (Galeria):
Uma grelha de cartazes com fotos de "Antes e Depois" ou pequenas mensagens de adotantes felizes que complementa o vídeo institucional com o WebVTT (R2).*

* *Mapa de Localização ou Horários de Visita Interativos:
Uma secção de contactos e horários do abrigo com um design limpo em Grid, facilitando a visualização de morada, contactos e dias de portas abertas.*

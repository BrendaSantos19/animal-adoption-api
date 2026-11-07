# 1. API Escolhida e Endpoints a Utilizar

- **API Selecionada:** API Laravel de Adoção de Animais (desenvolvida no semestre anterior), com autenticação baseada em tokens (Laravel Sanctum), controlo de acessos por papéis (RBAC) e respostas JSON padronizadas (`ApiResponse`).

## Endpoints Principais que a Aplicação vai Consumir

- **Públicos (Sem autenticação):** `GET /api/animals`, `GET /api/animals/{id}`, `POST /api/register`, `POST /api/login`
- **Autenticados (Adotante):** `GET /api/me`, `POST /api/applications`, `GET /api/my-applications`, `POST /api/refresh`, `POST /api/logout`
- **Autenticados (Staff):** `POST /api/register-staff`, `POST /api/animals`, `PATCH /api/animals/{id}`, `DELETE /api/animals/{id}`, `GET /api/applications`, `GET /api/animals/{id}/applications`, `PATCH /api/applications/{id}/status`, `GET /api/reports/adoptions`

# 2. Lista de Funcionalidades por Requisitos

- **R1 & R6 — Página de Apresentação (HTML, CSS e Acessibilidade):**
  * **Página estática responsiva:** Desenvolvida em HTML semântico e CSS próprio (com Flexbox e Grid) em formato mobile-first.
  * **Acessibilidade por Teclado:** Garantia de foco visual claro (`outline`) e navegação lógica por tecla `Tab` nos elementos interativos.
  * **Contraste de Cores WCAG:** Validação visual e de contraste para atingir uma pontuação > 90 no Google Lighthouse.
  * **Mapa de Localização e Horários de Visita:** Secção de contactos e horários do abrigo com design limpo em Grid, facilitando a visualização da morada e dias de portas abertas.

- **R2 — Multimédia:**
  * **Vídeo Institucional / Pós-Adoção:** Inclusão de um vídeo de testemunho de um adotante com o seu novo animal, acompanhado de legendas sincronizadas em formato `WebVTT`.
  * **Infografias informativas:** Elementos visuais sobre os passos da adoção, cuidados essenciais e responsabilidades de quem adota.
  * **Secção de Testemunhos / Histórias de Sucesso (Galeria):** Grelha de cartazes com mensagens de adotantes felizes para complementar o vídeo institucional.

- **R3 — JavaScript sem Framework:**
  * **Quiz / Formulário de Pré-Avaliação:** Questionário interativo em JavaScript puro (com manipulação do DOM e gestão de eventos) para avaliar a aptidão do adotante antes da submissão.

- **R4 — Aplicação React (SPA, Componentes e Rotas):**
  * **Listagem e Filtragem de Animais:** Componente de grelha reutilizável (`AnimalCard`) com filtros dinâmicos por espécie, tamanho e estado.
  * **Página de Detalhe:** Ficha completa e individualizada de cada animal.
  * **Formulários de Utilizador:** Ecrãs dedicados ao registo, login e submissão de candidaturas.

- **R5 — Integração com a API (Leitura, Escrita e Autenticação):**
  * **Operações de Leitura (GET):** Consulta de animais, perfil de utilizador, histórico pessoal de candidaturas e relatórios estatísticos do staff.
  * **Operações de Escrita (POST, PATCH, DELETE):** Registo de utilizadores, submissão de candidaturas, criação/atualização/remoção lógica de animais (Soft Delete) e alteração de estados de candidaturas.
  * **Autenticação por Token:** Gestão de sessões seguras através do Laravel Sanctum (`Bearer Token`) com separação de papéis (Adotante vs. Staff).
  * **Dashboard Analítico (Staff):** Utilização dos dados do endpoint de relatórios (`/api/reports/adoptions`) para construir cartões de resumo e indicadores visuais no painel do staff.
  * **Pesquisa Rápida de Candidaturas (Staff):** Barra de filtragem em tempo real por nome do adotante na listagem de processos administrativos.

- **R7 & R8 — Publicação e Documentação:**
  * **Deploy Online:** Publicação da aplicação web numa plataforma de alojamento (Vercel, Netlify ou GitHub Pages).
  * **Controle e Documentação:** Repositório Git com commits regulares e partilhados, acompanhado pelo `README.md` estruturado e pelo ficheiro `REGISTO_IA.md` completo.

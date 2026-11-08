# Projeto (M3) · Proposta (P1)

**Unidade Curricular:** Programação Web (Front-End) · 2026/2027  
**Autores:** Alexandra Martins e Brenda Santos

## 1. API Escolhida e Endpoints a Utilizar

**API Selecionada:** API Laravel de Adoção de Animais (desenvolvida no semestre anterior), com autenticação baseada em tokens (Laravel Sanctum), controlo de acessos por papéis (RBAC) e respostas JSON padronizadas (`ApiResponse`).

### Endpoints Principais que a Aplicação vai Consumir:
* **Públicos (Sem autenticação):** `GET /api/animals`, `GET /api/animals/{id}`, `POST /api/register`, `POST /api/login`
* **Autenticados (Adotante):** `GET /api/me`, `POST /api/applications`, `GET /api/my-applications`, `POST /api/refresh`, `POST /api/logout`
* **Autenticados (Staff):** `POST /api/register-staff`, `POST /api/animals`, `PATCH /api/animals/{id}`, `DELETE /api/animals/{id}`, `GET /api/applications`, `GET /api/animals/{id}/applications`, `PATCH /api/applications/{id}/status`, `GET /api/reports/adoptions`

---

## 2. Lista de Funcionalidades por Requisitos

* **R1 & R6 - Página de Apresentação (HTML, CSS e Acessibilidade):**
  * *Página estática responsiva:* Desenvolvida em HTML semântico e CSS próprio (com Flexbox e Grid) em formato mobile-first.
  * *Acessibilidade por Teclado:* Garantia de foco visual claro (`outline`) e navegação lógica por tecla `Tab` nos elementos interativos.
  * *Contraste de Cores WCAG:* Validação visual e de contraste para atingir uma pontuação $> 90$ no Google Lighthouse.
  * *Mapa de Localização e Horários de Visita:* Secção de contactos e horários do abrigo com design limpo em Grid.

* **R2 - Multimédia:**
  * *Vídeo Institucional / Pós-Adoção:* Vídeo de testemunho de um adotante com o seu novo animal, acompanhado de legendas sincronizadas em formato `WebVTT`.
  * *Infografias informativas:* Elementos visuais sobre os passos da adoção e responsabilidades.
  * *Secção de Testemunhos / Histórias de Sucesso:* Grelha de cartazes com mensagens de adotantes felizes.

* **R3 - JavaScript sem Framework:**
  * *Quiz / Formulário de Pré-Avaliação:* Questionário interativo em JavaScript puro (manipulação do DOM e eventos) para avaliar a aptidão do adotante antes da submissão.

* **R4 - Aplicação React (SPA, Componentes e Rotas):**
  * *Listagem e Filtragem de Animais:* Componente reutilizável (`AnimalCard`) com filtros dinâmicos por espécie, tamanho e estado.
  * *Página de Detalhe:* Ficha completa e individualizada de cada animal.
  * *Formulários de Utilizador:* Ecrãs dedicados ao registo, login e submissão de candidaturas.

* **R5 - Integração com a API (Leitura, Escrita e Autenticação):**
  * *Operações de Leitura (GET) e Escrita (POST, PATCH, DELETE):* Gestão completa de utilizadores, candidaturas e animais (com Soft Deletes).
  * *Autenticação por Token:* Sessões seguras via Laravel Sanctum (Bearer Token) com separação de papéis (Adotante vs. Staff).
  * *Dashboard Analítico (Staff):* Indicadores visuais e cartões de resumo baseados no endpoint de relatórios (`/api/reports/adoptions`).
  * *Pesquisa Rápida de Candidaturas (Staff):* Barra de filtragem por nome do adotante na listagem de processos.

* **R7 & R8 - Publicação e Documentação:**
  * *Deploy Online:* Publicação da aplicação web numa plataforma de alojamento (Vercel, Netlify e GitHub Pages).
  * *Controlo e Documentação:* Repositório Git com commits partilhados, `README.md` e `REGISTO_IA.md` completo.

---

## 3. Regras de Negócio e Validações Preventivas

Para garantir a coerência entre a aplicação Front-End (SPA React) e o Back-End em Laravel, foram mapeadas as seguintes regras de negócio e validações no lado do cliente:

* **Autenticação Obrigatória para Candidaturas:**
  * O acesso aos fluxos de submissão de candidaturas é restrito a adotantes devidamente registados e autenticados com token Sanctum (`auth:adopter`). Visitantes não autenticados são redirecionados automaticamente para os ecrãs de login ou registo.
* **Validação Proativa do Estado do Animal:**
  * A interface do React verifica o estado de cada animal (`status`). O sistema bloqueia ou desativa preventivamente o botão de submissão de candidatura para animais que não se encontrem com o estado `available` (disponível).
* **Restrição do Limite de Candidaturas Ativas:**
  * Para espelhar as restrições invioláveis da base de dados e prevenir abusos, o front-end valida o histórico pessoal do adotante, limitando o número máximo de candidaturas ativas em simultâneo.
* **Fluxo Condicionante de Pré-Avaliação (Quiz Obrigatório - R3):**
  * O questionário de pré-avaliação gerido em JavaScript puro serve como filtro de aptidão para a adoção.
  * *Regra de Acesso Restrito:* A aplicação impede o acesso direto à página de preenchimento dos dados de nova candidatura caso o adotante ainda não tenha respondido ao quiz. Se o utilizador tentar aceder à rota de submissão sem o quiz concluído, é redirecionado obrigatoriamente para o questionário.
* **Validade Temporal do Quiz de Pré-Avaliação (Renovação Semestral):**
  * Para garantir que as condições de acolhimento e o perfil do adotante se mantêm atualizados, o estado de aptidão obtido através do quiz tem uma validade regulamentada de **6 meses**.
  * Caso o período expire, o sistema invalida automaticamente o registo anterior e obriga o utilizador a responder novamente ao questionário antes de avançar com novas candidaturas, assegurando que o abrigo dispõe de dados rigorosos e correntes.
  * Após a conclusão (inicial ou renovada), o estado fica associado ao perfil (`GET /api/me`), permitindo submissões fluidas dentro do período de validade.

---

## 4. Divisão de Tarefas

* **Alexandra Martins - Responsável pelo Front-End Estático, Multimédia e Acessibilidade:**
  * Foca-se em toda a base da página de apresentação e interatividade sem frameworks, garantindo as bases visuais e de design.
  * *Tarefas Principais:*
    * **R1 & R6:** Desenvolvimento da página em HTML semântico e CSS próprio (Flexbox, Grid, mobile-first), garantia de acessibilidade por teclado (`Tab`, `outline`) e contraste WCAG ($\ge 90$ no Lighthouse).
    * **R2:** Integração do vídeo institucional / pós-adoção com legendas `WebVTT`, infografias informativas e galeria de testemunhos.
    * **R3:** Programação do quiz interativo de pré-avaliação em JavaScript puro (manipulação do DOM e gestão de eventos).
    * **Mapa e Horários:** Implementação da secção estática de contactos e horários com Grid.

* **Brenda Santos - Responsável pela SPA React, Rotas e Integração com a API:**
  * Foca-se na aplicação de página única em React com Vite, gestão de estado e comunicação direta com o back-end Laravel.
  * *Tarefas Principais:*
    * **R4:** Configuração do projeto com Vite, sistema de rotas (`react-router-dom`) e componentes reutilizáveis (`Navbar`, `AnimalCard`, etc.), listagem e página de detalhe.
    * **R5:** Serviços de comunicação HTTP (fetch/axios) para leitura e escrita, gestão de sessões por token Sanctum e separação de papéis (Adotante vs. Staff).
    * **Formulários e Autenticação:** Ecrãs de login/registo e submissão de candidaturas com gestão de estados de carregamento, vazio e erro.
    * **Funcionalidades do Staff:** Dashboard analítico baseado no endpoint de relatórios (`/api/reports/adoptions`) e barra de pesquisa rápida de candidaturas.

* **Tarefas Partilhadas:**
  * **R7 & R8 (Deploy e Documentação):** Deploy final (Netlify/Vercel/GitHub Pages), escrita conjunta do `README.md` e preenchimento regular/individual do `REGISTO_IA.md`.
  * **Controlo de Versões:** Commits regulares e distribuídos por ambos os elementos no repositório Git.

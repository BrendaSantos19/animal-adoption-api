# **API Adoção Animais**

Projeto de uma API REST completa para gestão de processos de adoção de animais, controlo de candidaturas, controlo de acessos baseado em papéis (RBAC), paginação, filtros e relatórios consolidados.

## **Tabela de Conteúdos**

1. [Instalação e Configuração](#1-instalação-e-configuração)
2. [Como executar o projeto](#2-como-executar-o-projeto)
3. [Estrutura da Base de Dados (Migrações)](#3-estrutura-da-base-de-dados-migrações)  
4. [Camada de Modelos (Models)](#4-camada-de-modelos-models)  
5. [Respostas Padronizadas (Helper)](#5-respostas-padronizadas-helper)  
6. [Alimentação da Base de Dados (Seeders)](#6-alimentação-da-base-de-dados-seeders)  
7. [Segurança e Controlo de Acesso (Middlewares RBAC)](#7-segurança-e-controlo-de-acesso-middlewares-rbac)  
8. [Controladores (Controllers)](#8-controladores-controllers)  
9. [Rotas da Aplicação (routes/api.php)](#9-rotas-da-aplicação-routesapiphp)  
10. [Modelação e Arquitetura (Diagrama ER)](#10-modelação-e-arquitetura)  
11. [Referência Completa de Endpoints](#11-referência-completa-de-endpoints)

## **Estrutura do projeto**
animal-adoption-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/   # Lógica de negócio
│   │   ├── Middleware/    # Filtros de acesso (RBAC)
│   │   ├── Resources/     # Formatação de saída JSON
│   │   └── Responses/     # Classe ApiResponse (Helper)
│   └── Models/            # Modelos e Eloquent
├── bootstrap/             # Configuração de aliases e kernel
├── database/
│   ├── migrations/        # Estrutura da base de dados
│   └── seeders/           # Dados de teste
├── routes/                # Definição dos endpoints (api.php)
└── .env                   # Configurações do ambiente

## **1. Instalação e Configuração**

### **Criar o projeto Laravel**
```bash
composer create-project laravel/laravel animal-adoption-api
cd animal-adoption-api
```
### **Garantir que o ficheiro SQLite existe**
```bash
New-Item -ItemType File -Path "database/database.sqlite" -Force
```
### **Verificar se foi criado com sucesso:**
```bash
ls database
```
### **Instalar e configurar o Sanctum**
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```
### **Configurar Variáveis de Ambiente e Autenticação**

#### **Atualizar o ficheiro .env**
Para garantir a portabilidade e facilidade de teste do projeto, configuramos a aplicação para utilizar SQLite. Certifica-te de que o ficheiro database/database.sqlite existe e atualiza o .env com as seguintes definições:
```bash
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
SESSION_DRIVER=file
```
#### **Gerar chave da aplicação**
```bash
php artisan key:generate
```
#### **Configurar o Sanctum para Múltiplos Models (config/auth.php)**
Para suportar o sistema de autenticação de Adopter e Staff de forma isolada, foram implementados Guards e Providers específicos no config/auth.php.

Esta arquitetura permite que a API valide credenciais de forma independente para cada perfil através do Laravel Sanctum. Desta forma, o controlo de acesso é executado ao nível da camada de autenticação, restringindo rotas administrativas (staff) ou de utilizador (adopter) de forma segura, garantindo que a separação de responsabilidades e a integridade do sistema sejam mantidas desde a entrada da requisição.

## **2. Como executar o projeto**
Para configurar e testar esta API localmente, segue estes passos:

Instalar dependências:
```bash
composer install
```
Configurar o ambiente:
Copia o ficheiro .env.example para .env.

Gera a chave da aplicação:
```bash
php artisan key:generate
```
Preparar a base de dados:
```bash
php artisan migrate:fresh --seed
```
Iniciar o servidor:
```bash
php artisan serve
```
## **3. Estrutura da Base de Dados (Migrações)**

### **Criar as Migrações**
```bash
php artisan make:migration create_animals_table
php artisan make:migration create_adopters_table
php artisan make:migration create_staff_table
php artisan make:migration create_applications_table
```
#### **Migração de Animais (database/migrations/xxxx_create_animals_table.php)**
Esta tabela centraliza as informações sobre os animais disponíveis para adoção.

    Configuração: Utiliza enum para o campo status, garantindo que o animal só possa estar em estados controlados (available, unavailable, adopted).

    Destaque: A inclusão do softDeletes() é crucial aqui, permitindo que, se um registo for apagado por engano, seja possível recuperá-lo, preservando o histórico da base de dados.

#### **Migração de Adotantes (database/migrations/xxxx_create_adopters_table.php)**
Representa os utilizadores finais (adotantes) que interagem com o sistema.

    Configuração: O campo email possui a restrição unique(), prevenindo o registo duplicado de utilizadores.

    Flexibilidade: O campo phone é opcional (nullable), permitindo que o perfil seja criado apenas com o essencial, sendo completado posteriormente pelo utilizador.


#### **Migração de Staff (database/migrations/xxxx_create_staff_table.php)
Dedicada à equipa administrativa que gere o sistema.

    Configuração: Segue o mesmo padrão de segurança dos adotantes, com autenticação própria.

    Objetivo: Esta separação de tabelas (adopters vs staff) permite que o sistema gerencie permissões distintas através do sistema de guards do Laravel.

#### **Migração de Candidaturas (database/migrations/xxxx_create_applications_table.php)
Esta tabela é uma tabela pivô com atributos, que liga o Adopter ao Animal.

    Integridade: Utiliza foreignId com constrained() e onDelete('cascade'). Isso garante que, se um animal ou um adotante for removido do sistema, as suas candidaturas associadas sejam limpas automaticamente, evitando registos "órfãos".

    Controlo de Fluxo: O campo status gere todo o ciclo de vida da candidatura, enquanto o campo rejection_reason oferece transparência ao adotante caso a sua tentativa seja negada.


#### **Executar todas as migrações**
```bash
php artisan migrate
```
## **4. Camada de Modelos (Models)**

### **Criar os Modelos**
```bash
php artisan make:model Animal  
php artisan make:model Adopter  
php artisan make:model Staff  
php artisan make:model Application
```
#### **Modelo de Animais (app/Models/Animal.php)**
Representa a entidade central da aplicação.

    Função: Armazena os dados dos animais e o seu estado de adoção (available, unavailable, adopted).

    Relações: Possui uma relação de um-para-muitos (hasMany) com Application, permitindo que um animal tenha múltiplas candidaturas associadas.

#### **Modelo de Adotantes (app/Models/Adopter.php) e Staff (app/Models/Staff.php)**
São os modelos responsáveis pela autenticação no sistema.

    Configuração: Ambos estendem Authenticatable e utilizam HasApiTokens (Laravel Sanctum), permitindo a emissão de tokens de API para autenticação stateless.

    Segurança: O campo password está incluído no array $hidden, garantindo que a palavra-passe nunca seja exposta em respostas JSON da API.

#### **Modelo de Candidaturas (app/Models/Application.php)**
O modelo que estabelece a ponte entre o adotante e o animal.

    Função: Regista o interesse de um adotante num animal.

    Relações: Utiliza relações belongsTo tanto para Animal quanto para Adopter, permitindo aceder facilmente aos detalhes de quem se candidatou e a qual animal.

## **5. Respostas Padronizadas (Helper)**

### **Criar Classe Helper de Resposta (app/Http/Responses/ApiResponse.php)**
Para assegurar a consistência e previsibilidade da API, foi implementada uma classe estática ApiResponse. Esta classe garante que todas as respostas seguem o mesmo contrato de dados, simplificando o tratamento de erros no cliente.

Estrutura de resposta:
*Sucesso (200 OK / 201 Created):*
    JSON
    {
    "data": { ... },
    "meta": { ... },
    "errors": null
    }  
*Erro (4xx / 5xx):*
    JSON
    {
    "data": null,
    "meta": null,
    "errors": { "message": "Descrição do erro" }
    }

### **Camada de Apresentação (API Resources)**
```bash
php artisan make:resource ApplicationsResource
php artisan make:resource AnimalResource
```
Para garantir que a API é eficiente e segura, utilizamos os *Laravel API Resources*. Esta camada atua como um intermediário entre os Modelos e a resposta JSON final.

    Abstração: O cliente da API não conhece a estrutura real da base de dados, apenas o contrato definido nos Resources.

    Ocultação de Dados: Campos sensíveis (como password, deleted_at, ou chaves internas) são automaticamente filtrados, prevenindo fugas de dados (Data Exposure).

    Transformação: Permite formatar dados complexos (como converter datas para Y-m-d ou incluir apenas campos específicos de entidades relacionadas, como o breed do animal dentro da candidatura).

## **6. Alimentação da Base de Dados (Seeders)**

### **Criar os Seeders**
```bash
php artisan make:seeder AnimalSeeder  
php artisan make:seeder AdopterSeeder  
php artisan make:seeder StaffSeeder  
php artisan make:seeder ApplicationSeeder
```
Para facilitar a demonstração e os testes, criamos Seeders que preenchem a base de dados com um conjunto de dados iniciais. Isto permite verificar imediatamente:

    Autenticação: Contas de Staff e Adopter pré-criadas.

    Estado de Adoção: Animais em diferentes estados (available, adopted, etc.).

    Processos de Negócio: Candidaturas (applications) em vários estágios (submitted, approved, rejected), incluindo motivos de rejeição.

#### **Executar migrações e popular base de dados:**
```bash
php artisan migrate:fresh --seed
```
## **7. Segurança e Controlo de Acesso (Middlewares RBAC)**
    Para garantir que apenas os utilizadores com o perfil correto possam aceder a determinadas rotas (ex: apenas Staff pode criar animais), implementamos filtros de middleware personalizados.

### **Criar Filtros de Controlo de Acesso**
```bash
php artisan make:middleware IsStaff  
php artisan make:middleware IsAdopter
```
#### **Middleware Staff (app/Http/Middleware/IsStaff.php) e Adotante (app/Http/Middleware/IsAdopter.php)**
Os Middlewares IsStaff e IsAdopter atuam como "porteiros" da API. Eles verificam a instância do utilizador autenticado ($request->user()) antes de permitir que a requisição chegue ao controlador.

    IsStaff: Restringe o acesso a rotas administrativas (ex: criação de novos funcionários, relatórios e gestão de estatutos de candidatura).

    IsAdopter: Restringe o acesso a rotas exclusivas do utilizador final (ex: submissão de candidaturas).

#### **Registar os Aliases (bootstrap/app.php)**
O registo foi efetuado no ficheiro bootstrap/app.php através da definição de aliases, permitindo que sejam aplicados de forma limpa nas rotas da API.

## **8. Controladores (Controllers)**
A lógica de negócio foi organizada de forma modular, com cada controlador a responder por um domínio específico da aplicação, garantindo a separação de responsabilidades.

### **Criar controllers**
```bash
php artisan make:controller AuthController
php artisan make:controller AnimalController
php artisan make:controller ApplicationController
php artisan make:controller AnimalApplicationController
php artisan make:controller ReportController
php artisan make:controller StaffController
```
#### **Controlador de Autenticação (app/Http/Controllers/AuthController.php)**
Implementação de um fluxo unificado de autenticação que suporta múltiplos guards (adopter e staff), utilizando *instanceof* para identificação de tipos de forma segura.
**Boas Práticas Implementadas:**
    Prevenção de Problema N+1: Utilização de $query->with(['animal', 'adopter']) para otimizar o carregamento de relações.

    Segurança de Acesso: Verificação de permissões e regras de negócio diretamente nos métodos de criação e atualização.

    Padronização: Todos os controladores utilizam a classe ApiResponse para garantir que o formato JSON de saída seja sempre previsível.

#### **Controlador de Animais (app/Http/Controllers/AnimalController.php)**
Suporte a filtragem dinâmica de resultados (espécie, tamanho, idade) com paginação integrada para performance.

#### **Controlador de Candidaturas (app/Http/Controllers/ApplicationController.php)**
Implementação de Transações Atómicas (DB::transaction). Isto garante que, ao aprovar uma candidatura, o sistema altera o estado da candidatura e do animal de forma sincronizada ou não altera nada, evitando inconsistências na base de dados.

#### **Controlador de Relatórios (app/Http/Controllers/ReportController.php)**
Utilização de Query Builder avançado com agregações (COUNT, AVG, GROUP BY) para fornecer métricas de negócio, como taxa de adoção e tempo médio de espera.

## **9. Rotas da Aplicação (routes/api.php)**

### **Configuração Completa do Ficheiro de Rotas**
A API foi estruturada para ser intuitiva e segura, utilizando grupos de rotas protegidos por Middlewares e Guards específicos. Esta organização garante que as permissões de acesso sejam verificadas no momento da entrada.

#### **Níveis de acesso**
| Grupo    | Objetivo                                      | Autenticação       | 
| -------- | --------------------------------------------- | ------------------ | 
| Público  | Listagem e visualização de animais            | Livre acesso       |           
| Comum    | Gestão de conta (me, logout, refresh, update) | auth:adopter,staff |  
| Adotante | Submissão e consulta de candidaturas          | auth:adopter       |  
| Staff    | Gestão de animais, staff e relatórios         | auth:staff         |  


#### **Estratégia de Roteamento**
    Throttle: Todas as rotas autenticadas possuem um limite de 60 requisições por minuto (throttle:60,1) para prevenir ataques de brute-force e sobrecarga do servidor.

    Isolamento: A utilização de guards distintos (adopter e staff) assegura que um utilizador de um perfil não consiga, por erro ou manipulação, aceder a rotas exclusivas do outro perfil.

    Flexibilidade: A criação de um grupo "Comum" evita a duplicação de código para endpoints como o /me ou /logout, mantendo a arquitetura D.R.Y (Don't Repeat Yourself).
    
## **10. Modelação e Arquitetura**

### **Diagrama ER**
+-------------+        +------------------+        +-------------+  
|   Adopter   | 1    N |   Application    | N    1 |    Animal   |  
+-------------+        +------------------+        +-------------+  
| id          |        | id               |        | id          |  
| name        |        | animal_id        |        | species     |  
| email       |        | adopter_id       |        | breed       |  
| password    |        | status           |        | age         |  
| phone       |        | rejection_reason |        | size        |  
+-------------+        +------------------+        | status      |  
                                                   +-------------+

+--------------+  
|     Staff    |  
+--------------+  
| id           |  
| name         |  
| email        |  
| password     |  
+--------------+

### **Decisões de Design**

**Sanctum Polimórfico:** Utilizamos o Laravel Sanctum para autenticação por tokens, permitindo uma separação clara entre as entidades Adopter e Staff sem a necessidade de um sistema de autorização monolítico. Isto facilita a gestão de permissões distintas.

**Segurança em Camadas:** Combinamos Guards (para autenticação de identidade) com Middlewares RBAC (para autorização de ações), criando uma defesa em profundidade.

**Persistência Histórica (SoftDeletes):** Implementamos SoftDeletes em modelos críticos como Animal. O sistema não remove registos fisicamente, permitindo que a administração recupere informações importantes, minimizando o impacto de operações acidentais.

**Eficiência de Dados:** Utilizamos paginação de tamanho fixo em endpoints de listagem e Eager Loading (with()) para prevenir o problema de consultas N+1, otimizando drasticamente a performance da base de dados. 

### **Tratamento de Casos de Fronteira (Edge Cases)**

**Integridade Transacional:** Utilizamos DB::transaction em operações sensíveis (como a aprovação de uma adoção). Isto garante que, se algo falhar entre a atualização do estado da candidatura e a atualização do status do animal, a base de dados reverte para um estado consistente.

**Validação Proativa:** Todas as entradas de dados passam por validações rigorosas antes de chegarem à camada de persistência, bloqueando payloads incompatíveis.

**Regras de Negócio Invioláveis:** Implementamos restrições condicionais, como o limite de 3 candidaturas ativas por adotante e o bloqueio de candidaturas para animais que não estejam com o status available.

### **Observações Importantes para Operação**

**Consistência JSON:** Todas as respostas da API, sejam erros ou sucesso, seguem um contrato estrito definido na classe ApiResponse. Isto facilita a integração com qualquer front-end. 

**Gestão de Datas:** As datas de criação e atualização nas respostas são formatadas de forma consistente via API Resources, garantindo uma interface previsível para o consumidor final.

## **11. Referência Completa de Endpoints**

### **Autenticação**

| Método | Endpoint             | Acesso      | Descrição                                     |
| ------ | -------------------- | ----------- | --------------------------------------------- |
| POST   | /api/register        | Público     | Registar uma conta nova de adotante           |
| POST   | /api/login           | Público     | Autenticar utilizador (devolve token Sanctum) |
| GET    | /api/me              | Autenticado | Obter o perfil do utilizador ligado           |
| POST   | /api/refresh         | Autenticado | Revogar e repor um novo token de acesso       |
| PATCH  | /api/me              | Autenticado | Atualiza dados do utilizador                  |
| POST   | /api/logout          | Autenticado | Terminar a sessão e revogar token atual       |
| POST   | /api/register-staff  | Staff       | Registar um novo utilizador administrativo    |

### **Animais**

| Método | Endpoint                       | Acesso   | Descrição                                       |
| ------ | ------------------------------ | -------- | ----------------------------------------------- |
| GET    | /api/animals                   | Público  | Listar animais (Filtros: species, size, status) |
| GET    | /api/animals/{id}              | Público  | Consultar a ficha detalhada de um animal        |
| POST   | /api/animals                   | Staff    | Registar um animal no abrigo                    |
| PATCH  | /api/animals/{id}              | Staff    | Atualizar dados cadastrais de um animal         |
| DELETE | /api/animals/{id}              | Staff    | Remover animal do abrigo (Soft Delete)          |

### **Candidaturas**

| Método | Endpoint                       | Acesso      | Descrição                                    |
| ------ | ------------------------------ | ----------- | -------------------------------------------- |
| POST   | /api/applications              | Adotante    | Submeter nova candidatura para adoção        |
| GET    | /api/my-applications           | Adotante    | Consultar candidaturas do próprio utilizador |
| GET    | /api/applications              | Staff       | Consultar histórico paginado de candidaturas |
| GET    | /api/animals/{id}/applications | Staff       | Consultar candidaturas para um animal        |
| PATCH  | /api/applications/{id}/status  | Staff       | Atualizar o estado da candidatura            |
| GET    | /api/reports/adoptions         | Staff       | Gerar relatórios estatísticos                |


**Nota:** Toda a comunicação protegida exige o envio de um header Authorization: Bearer \<token\>.





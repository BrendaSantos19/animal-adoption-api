# Relatório de Atividade: Configuração e Envio de API Laravel para o GitHub

Este documento resume o processo de versionamento e envio de uma API desenvolvida em Laravel (no VS Code) para um repositório remoto no GitHub.

## 1. Objetivo Inicial
Esclarecer a necessidade de colocar o código-fonte de um projeto/API em um repositório do GitHub (requisito acadêmico) e realizar o fluxo completo de publicação de forma guiada.

## 2. Diagnóstico e Identificação Tecnológica
* **Ambiente:** Visual Studio Code (VS Code) no Windows.
* **Framework:** Laravel (PHP), identificado através de arquivos como `artisan`, `composer.json` e pastas estruturais (`app`, `database`, `routes`, etc.).

## 3. Etapas Executadas no Terminal

1. **Inicialização do Repositório Local:**
   ```bash
   git init
   ```
2. **Configuração de Identidade do Git:**
   Resolução da exigência de identificação do autor do commit:
   ```bash
   git config --global user.name "Seu Nome"
   git config --global user.email "seu-email@dominio.com"
   ```
3. **Adição e Commit Inicial:**
   Preparação dos arquivos ignorando dados sensíveis (.env) e pastas desnecessárias (como `vendor`, geridas pelo framework):
   ```bash
   git add .
   git commit -m "primeiro commit: estrutura inicial da API Laravel"
   ```
4. **Vinculação com o Repositório Remoto:**
   Conexão do repositório local ao repositório criado no GitHub:
   ```bash
   git remote add origin https://github.com/BrendaSantos19/animal-adoption-api.git
   git branch -M main
   ```
5. **Resolução de Conflitos (`README.md`):**
   Tratamento de divergências iniciais entre o histórico local e o arquivo gerado automaticamente pelo GitHub (utilizando `--allow-unrelated-histories` e ajuste manual do conflito).

## 4. Estado Final
Projeto configurado com sucesso, com todos os arquivos da API Laravel preparados e integrados ao repositório remoto no GitHub na branch `main`.

# Artchive 🎬🎶📷

**Artchive** é uma plataforma online de gestão de portfólios artísticos, desenvolvida para apoiar fotógrafos, videomakers e músicos amadores na criação e divulgação dos seus trabalhos. O sistema permite a criação de perfis personalizados e a partilha de conteúdos multimédia (imagens, vídeos e áudio), com controlo total sobre a visibilidade e organização dos mesmos.

## 📌 Funcionalidades

- ✅ Autenticação com dois fatores (e-mail)
- ✅ Sistema "Não sou um robô" no registo
- ✅ Criação de conta com imagem de perfil e biografia
- ✅ Login e logout
- ✅ Criação de perfis personalizados
- ✅ Submissão de imagens, vídeos e faixas de áudio
- ✅ Visualização de conteúdos por categoria
- ✅ Modos público e privado para os conteúdos
- ✅ Pesquisa de conteúdos e utilizadores
- ✅ Sistema de likes/dislikes e follow/unfollow
- ✅ Interface gráfica apelativa e amigável
- ✅ Envio automático de emails pela secção de contactos

# 🧑‍💻 Tipos de Utilizadores

- **Administrador**: controlo total sobre o sistema, utilizadores e categorias principais
- **Simpatizante**: pode carregar conteúdos, gerir portfólios, criar categorias secundárias
- **Utilizador**: explora conteúdos públicos e subscreve categorias
- **Convidado**: navega e pesquisa conteúdos públicos sem login

# 🗃️ Estrutura Tecnológica

- **Frontend:** HTML5, CSS3, JavaScript (AJAX)
- **Backend:** PHP
- **Base de Dados:** MySQL
- **Outros:** Google reCAPTCHA, serviço de envio de e-mails

# 🚀 Como Executar o Projeto

1. No Docker, correr o container "smi-web-v5"
2. Importar o ficheiro `artchive.sql` para o phpMyAdmin no ```http://localhost/dashboard/```
3. Mover a pasta 'Artchive' para "examples-smi"
4. Executar o projeto no navegador:
    ```
    http://localhost/examples-smi/Artchive
    ```

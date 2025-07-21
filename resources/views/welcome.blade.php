<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>To-Do List</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        <div class="container mt-5">
            <h1 class="text-center mb-4">Exemplos de Componentes Vue.js</h1>

            <!-- Componente de exemplo original -->
            <div class="mb-5">
                <h2>Componente de Exemplo</h2>
                <example-component></example-component>
            </div>

            <!-- Novo componente de To-Do List -->
            <div class="mb-5">
                <h2>To-Do List Funcional</h2>
                <todo-list></todo-list>
            </div>
        </div>
    </div>
</body>

</html>

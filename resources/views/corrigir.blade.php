<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Corrigir Redação</title>
</head>
<body>
    <h1>Corrigir Redação</h1>
    <form method="POST" action="/corrigir-redacao">
        @csrf
        <label for="texto">Cole sua redação:</label><br>
        <textarea name="texto" id="texto" rows="15" cols="80"></textarea><br><br>
        <button type="submit">Corrigir</button>
    </form>
</body>
</html>
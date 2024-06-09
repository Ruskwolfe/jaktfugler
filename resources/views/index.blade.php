<!DOCTYPE html>
<html>
<head>
    <title>Jaktfugler</title>
</head>
<body>
    <h1>Sjekk Jakttider for Fugler i Norge</h1>
    <form action="/sjekk" method="POST">
        @csrf
        <label for="art_input">Skriv inn fuglenavn:</label>
        <input type="text" id="art_input" name="art_input">
        <button type="submit">Sjekk Jakttider</button>
    </form>
    
    <form action="/liste" method="POST">
        @csrf
        <button type="submit">Vis alle arter</button>
    </form>

    @if (isset($result))
        <div>
            <h2>Resultat:</h2>
            <p>{!! $result !!}</p>
        </div>
    @endif
</body>
</html>

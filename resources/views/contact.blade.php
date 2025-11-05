<h1>Bize Ulaşın</h1>
<form action="{{ route('contact.store') }}" method="POST">
    @csrf
    <input type="text" name="name" placeholder="Adınız">
    <input type="email" name="email" placeholder="E-posta">
    <input type="text" name="subject" placeholder="Konu">
    <textarea name="message" placeholder="Mesajınız"></textarea>
    <button type="submit">Gönder</button>
</form>

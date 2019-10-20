@if ($errors->any())        <!-- prosty blade-owy if i metoda zwracająca 1 jeśli jest jakikolwiek obiekt $error  -->
    <div class="notifications bg-danger text-white alert">
        <ul class="mb-0 list-unstyled">
            @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>    
@endif
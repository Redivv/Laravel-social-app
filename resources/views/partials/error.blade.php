@if ($errors->any())        <!-- prosty blade-owy if i metoda zwracająca 1 jeśli jest jakikolwiek obiekt $error  -->
    <div class="notifications bg-danger text-white alert">
        <ul class="mb-0 list-unstyled">
            @error('age-min')
                <li>{{__('searcher.min-age-err')}}</li>
            @enderror
            @error('age-min')
                <li>{{__('searcher.max-age-err')}}</li>
            @enderror
        </ul>
    </div>    
@endif
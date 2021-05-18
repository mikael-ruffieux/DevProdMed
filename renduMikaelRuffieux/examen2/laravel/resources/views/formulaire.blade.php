<!DOCTYPE html>
<html>
    <body>
        <form action="{{url('traiteQuizz')}}" method="post" accept-charset="UTF-8">
            @csrf
            <div>
                <label for="Prenom">Prenom:</label><br>
                <input type="text" id="Prenom" name="prenom" value="{{old('prenom')}}"><br>
                {!! $errors->first('prenom', '<small class="help-block">:message</small><br>') !!}
                <label for="Age">Age (Entre 1 and 128):</label><br>
                <input type="number" id="Age" name="age" min="1" max="128" value="{{old('age')}}"><br>
                {!! $errors->first('age', '<small class="help-block">:message</small>') !!}
            </div>
            <br>
            <div>
                <label for="Question1">Choisi les languages que tu connais:</label>
                <select id="Question1" name="q1[]" multiple>
                    <option value="r1-1" {{ null!== old('q1') ? (in_array("r1-1", old('q1')) ? 'selected' : '') : '' }}>Java</option>
                    <option value="r1-2" {{ null!== old('q1') ? (in_array("r1-2", old('q1')) ? 'selected' : '') : '' }}>C#</option>
                    <option value="r1-3" {{ null!== old('q1') ? (in_array("r1-3", old('q1')) ? 'selected' : '') : '' }}>PHP</option>
                    <option value="r1-4" {{ null!== old('q1') ? (in_array("r1-4", old('q1')) ? 'selected' : '') : '' }}>Javascript</option>
                </select>
                <br>
                {!! $errors->first('q1', '<small class="help-block">:message</small>') !!}
            </div>
            <br>

            <div role="radiogroup" aria-labelledby="genre_label">
                <p id="genre_label">Quel est ton genre ?</p>
                <label><input type="radio" name="genre" value="Homme" {{ old('genre') == "Homme" ? 'checked' : '' }}> Homme</label>
                <label><input type="radio" name="genre" value="Femme" {{ old('genre') == "Femme" ? 'checked' : '' }}> Femme</label>
                <label><input type="radio" name="genre" value="Autre" {{ old('genre') == "Autre" ? 'checked' : '' }}> Autre</label>
            </div>
            {!! $errors->first('genre', '<small class="help-block">:message</small>') !!}
            <br>
            
            <div role="checkbox" aria-labelledby="preferences_label">
                <p id="preferences_label">Qu'aimes-tu faire ?</p>
                <label><input type="checkbox" name="preferences[]" value="Concevoir" {{ null!== old('preferences') ? (in_array("Concevoir", old('preferences')) ? 'checked' : '') : '' }}> Concevoir</label>
                <label><input type="checkbox" name="preferences[]" value="Implementer" {{ null!== old('preferences') ? (in_array("Implementer", old('preferences')) ? 'checked' : '') : '' }}> Implementer</label>
                <label><input type="checkbox" name="preferences[]" value="Tester" {{ null!== old('preferences') ? (in_array("Tester", old('preferences')) ? 'checked' : '') : '' }}> Tester</label>
                
            </div>
            {!! $errors->first('preferences', '<small class="help-block">:message</small>') !!}
        
            <div>
                <input type="submit" value="Submit">
            </div>
        </form>
    </body>
</html>
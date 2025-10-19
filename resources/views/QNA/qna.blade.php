@extends('layouts.app')
@section('content')


<h1>Frequently Asked Question</h1>

<div class="container">
    @each('components.faq-item', $faqs, 'item')
    
    <br>
    <br>
    
    <form>
        <div class="isibox">
            <details>
                <p>Jika ada pertanyaan lain, silahkan kirim pertanyaanya di bawah ini.</p>
                <summary>Ada Pertanyaan?</summary>
            </details>
            <div class="Questionbox">
                <table>
                    <tr>
                        <td>Question Box</td>
                    </tr>
                    <tr>
                        <td><input type="text" name="box" id="box"></td>
                    </tr>

                    <tr>
                        <td><input type="button" value="Batal"></td> 
                        <td><input type="submit" value="Kirim"></td> 
                    </tr>
                </table>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/esa/QNA.js') }}"></script>
@endpush
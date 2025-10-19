@props(['class' => '', 'text' => ''])

<div class="step {{ $class }}">
<span class="icon">&#10003;</span>
<p class="text-sm font-semibold">{{ $text }}</p>
</div>

<style>
/* Styling khusus untuk Status Tracker (Menggunakan murni CSS, bukan Canvas) /
.status-tracker {
display: flex;
justify-content: space-between;
align-items: center;
margin-top: 30px;
padding: 20px 0;
position: relative;
/ Memberi ruang di sekitar tracker */
status-tracker{
max-width: 90%;
margin-left: auto;
margin-right: auto;
}
.status-tracker::before {
content: '';
position: absolute;
top: 50%;
left: 10%;
right: 10%;
height: 4px;
background-color: #e0e0e0;
transform: translateY(-50%);
z-index: 1;
}

.step {
display: flex;
flex-direction: column;
align-items: center;
text-align: center;
position: relative;
z-index: 2;
transition: all 0.5s ease-in-out;
width: 30%; /* Memberi lebar agar rata */
}

.step .icon {
width: 40px;
height: 40px;
background-color: #e0e0e0;
border-radius: 50%;
display: flex;
justify-content: center;
align-items: center;
color: #fff;
font-size: 18px;
font-weight: bold;
margin-bottom: 8px;
transition: background-color 0.5s;
}

.step.active .icon {
background-color: #4CAF50; /* Warna hijau untuk status aktif */
box-shadow: 0 0 0 5px rgba(76, 175, 80, 0.3);
}

.step.active p {
color: #4CAF50;
}

/* Garis konektor yang aktif /
/ Note: Logic ini memerlukan CSS eksternal atau struktur HTML yang berbeda untuk sempurna.
Di sini, kita menggunakan pseudo-class untuk visualisasi sederhana antar step aktif. */
.step.active + .step::before {
content: '';
position: absolute;
top: 50%;
left: 0;
width: 100%;
height: 4px;
background-color: #4CAF50;
transform: translateY(-50%);
z-index: -1;
}

/* Perbaikan untuk tampilan mobile /
@media (max-width: 600px) {
.status-tracker {
flex-direction: column;
align-items: flex-start;
padding-top: 0;
}
.status-tracker::before {
width: 4px;
height: 80%;
top: 10%;
left: 20px;
right: auto; / Menonaktifkan right 
transform: none;  */

.step {
flex-direction: row;
text-align: left;
margin-bottom: 30px;
width: 100%;
}
.step .icon {
margin-right: 15px;
margin-bottom: 0;
}


</style>
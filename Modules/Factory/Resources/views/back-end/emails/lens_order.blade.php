<h2>Lens Order Details</h2>

<h3>VA</h3>
<ul>
    <li>Code: {{ $data['VA']['code']['value'] ?? '-' }}</li>
</ul>

<h3>VA Amount</h3>
<ul>
    <li>Total: {{ $data['VA_amount']['total'] }}</li>
</ul>

<h3>Lens - Right</h3>
<ul>
    <li>Checked: {{ $data['Lens']['Right']['isCheck'] ? 'Yes' : 'No' }}</li>
    <li>Far:
        <ul>
            <li>SPH: {{ $data['Lens']['Right']['Far']['SPH'] }}</li>
            <li>CYL: {{ $data['Lens']['Right']['Far']['CYL'] }}</li>
            <li>Axis: {{ $data['Lens']['Right']['Far']['Axis'] }}</li>
        </ul>
    </li>
    <li>Near:
        <ul>
            <li>SPH: {{ $data['Lens']['Right']['Near']['SPH'] }}</li>
            <li>CYL: {{ $data['Lens']['Right']['Near']['CYL'] }}</li>
            <li>Axis: {{ $data['Lens']['Right']['Near']['Axis'] }}</li>
        </ul>
    </li>
    <li>Addition: {{ $data['Lens']['Right']['Addition'] }}</li>
    <li>Distance: {{ $data['Lens']['Right']['Distance'] }}</li>
    <li>Height: {{ $data['Lens']['Right']['Height'] }}</li>
</ul>

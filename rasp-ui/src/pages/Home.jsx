import { Link } from 'react-router-dom'
export default function Home() {
return (
<div className="container" style={{ padding: "40px", textAlign:
"center" }}>
<h1>Bienvenido a Rasp Server</h1>
<p>Servidor en ejecución sobre Apache + PHP en tu Raspberry Pi.</p>

<Link to="/admin" className="btn">Ir al panel de administración</Link>
</div>
)
}
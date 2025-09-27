import Card from '../components/Card'
export default function Dashboard() {
    return (
        <div className="container" style={{ padding: "20px" }}>
            <h1>Panel</h1>
            <div className="grid">
                <Card title="Estado del sistema">
                    <pre>Temp: (usar PHP sys_temp)</pre>
                    <pre>CPU, Mem, Disco..</pre>
                </Card>
                <Card title="Consola">
                    <a href="/admin/consola.php">Ir a consola avanzada</a>
                </Card>
                <Card title="Editor rápido">
                    <a href="/admin/editor.php">Abrir editor</a>
                </Card>
            </div>
        </div>
    )
}

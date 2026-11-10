import { useEffect, useState } from 'react'
import './App.css'

const API_URL = import.meta.env.VITE_API_URL ?? 'http://localhost:8000/api'

const emptyForm = { name: '', sku: '', stock: '', price: '' }

function App() {
  const [products, setProducts] = useState([])
  const [form, setForm] = useState(emptyForm)
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(true)

  async function loadProducts() {
    setLoading(true)
    try {
      const response = await fetch(`${API_URL}/products`)
      if (!response.ok) throw new Error('No fue posible consultar el inventario.')
      setProducts(await response.json())
    } catch (requestError) {
      setError(requestError.message)
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    loadProducts()
  }, [])

  function updateField(event) {
    setForm({ ...form, [event.target.name]: event.target.value })
  }

  async function createProduct(event) {
    event.preventDefault()
    setError('')
    try {
      const response = await fetch(`${API_URL}/products`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
        body: JSON.stringify({ ...form, stock: Number(form.stock), price: Number(form.price) }),
      })
      if (!response.ok) {
        const body = await response.json()
        throw new Error(Object.values(body.errors ?? {}).flat()[0] ?? 'No fue posible guardar el producto.')
      }
      setProducts([await response.json(), ...products])
      setForm(emptyForm)
    } catch (requestError) {
      setError(requestError.message)
    }
  }

  async function removeProduct(id) {
    if (!window.confirm('¿Eliminar este producto del inventario?')) return
    const response = await fetch(`${API_URL}/products/${id}`, { method: 'DELETE' })
    if (response.ok) setProducts(products.filter((product) => product.id !== id))
  }

  const totalUnits = products.reduce((sum, product) => sum + product.stock, 0)

  return (
    <main className="shell">
      <header className="header">
        <div>
          <span className="eyebrow">OPERACIONES / INVENTARIO</span>
          <h1>Control de inventario</h1>
          <p>Administra existencias y precios desde un solo lugar.</p>
        </div>
        <div className="brand-mark">IN</div>
      </header>

      <section className="metrics">
        <article><span>Productos registrados</span><strong>{products.length}</strong></article>
        <article><span>Unidades en stock</span><strong>{totalUnits}</strong></article>
        <article><span>Estado del sistema</span><strong className="online">● Operativo</strong></article>
      </section>

      <section className="content-grid">
        <form className="card form-card" onSubmit={createProduct}>
          <div className="card-heading"><h2>Nuevo producto</h2><span>+</span></div>
          <label>Nombre del producto<input name="name" value={form.name} onChange={updateField} placeholder="Ej. Teclado mecánico" required /></label>
          <label>SKU<input name="sku" value={form.sku} onChange={updateField} placeholder="Ej. TEC-001" required /></label>
          <div className="two-columns">
            <label>Stock inicial<input name="stock" type="number" min="0" value={form.stock} onChange={updateField} placeholder="0" required /></label>
            <label>Precio<input name="price" type="number" min="0" step="0.01" value={form.price} onChange={updateField} placeholder="0.00" required /></label>
          </div>
          <button type="submit">Agregar al inventario <span>→</span></button>
          {error && <p className="error">{error}</p>}
        </form>

        <section className="card table-card">
          <div className="card-heading"><div><h2>Productos</h2><p>Listado actual del inventario</p></div><button className="refresh" onClick={loadProducts}>↻ Actualizar</button></div>
          {loading ? <p className="empty">Cargando inventario...</p> : products.length === 0 ? <p className="empty">Aún no hay productos registrados.</p> : (
            <div className="table-wrap"><table><thead><tr><th>Producto</th><th>SKU</th><th>Stock</th><th>Precio</th><th></th></tr></thead>
              <tbody>{products.map((product) => <tr key={product.id}><td className="product-name">{product.name}</td><td><code>{product.sku}</code></td><td><span className={product.stock < 5 ? 'low-stock' : ''}>{product.stock} uds.</span></td><td>${Number(product.price).toFixed(2)}</td><td><button className="delete" onClick={() => removeProduct(product.id)} aria-label={`Eliminar ${product.name}`}>×</button></td></tr>)}</tbody>
            </table></div>
          )}
        </section>
      </section>
    </main>
  )
}

export default App

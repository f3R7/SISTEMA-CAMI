import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LayoutComponent } from './componentes/layout/layout.component';
import { CategoriasComponent } from './paginas/categorias/categorias.component';
import { IngresosComponent } from './paginas/ingresos/ingresos.component';
import { LoginComponent } from './paginas/login/login.component';
import { MarcasComponent } from './paginas/marcas/marcas.component';
import { ProductosComponent } from './paginas/productos/productos.component';
import { RolesComponent } from './paginas/roles/roles.component';
import { UsuariosComponent } from './paginas/usuarios/usuarios.component';

const routes: Routes = [
  {path: '', redirectTo: 'perfil', pathMatch: 'full'},
  { path: '',

  component: LayoutComponent,

  children: [
    {path: 'roles', component: RolesComponent},
    { path: 'usuarios', component: UsuariosComponent },
    { path: 'marcas', component: MarcasComponent },
    { path: 'categorias', component: CategoriasComponent },
    {path: 'productos', component: ProductosComponent},
    {path: 'ingresos', component: IngresosComponent},
  ],

  },
  { path: 'login', component: LoginComponent }

]

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }

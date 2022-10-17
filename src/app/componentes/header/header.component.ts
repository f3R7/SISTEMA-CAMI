import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit {
  nombre :string;
  foto: string;
  
  constructor(private httpClient: HttpClient) { }

  ngOnInit(): void {
    this.obtenerUsuario();
      }
    
      autorizar(){
        const tok =  localStorage.getItem('token-cami');
        const httpOptions = {
        headers: new HttpHeaders({
          'Content-Type':  'application/json',
          'Authorization': 'Bearer ' + tok
        })};
        return httpOptions;
      }
      obtenerUsuario(){
           
        let jwt =  localStorage.getItem('token-cami') as string ;
      
    let jwtData = jwt.split('.')[1]
    let decodedJwtJsonData = window.atob(jwtData)
    let decodedJwtData = JSON.parse(decodedJwtJsonData)
    
    let idpersona = decodedJwtData.data.persona;
    
    this.httpClient.get(`http://localhost/cami/api/usuarios/editperfil/${idpersona}`, this.autorizar()).subscribe((data: any)=>{
      this.nombre = data[0].Nombres + ' '+ data[0].Apellidos;
      this.foto = "http://localhost/cami/"+data[0].Foto;
    
    });
      }


  cerrar() { 
    localStorage.removeItem('token-cami');
    window.location.href="login";
  }
}

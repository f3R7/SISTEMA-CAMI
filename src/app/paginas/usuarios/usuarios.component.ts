import { HttpClient } from '@angular/common/http';
import { Component, OnDestroy, OnInit, ViewChild } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { DataTableDirective } from 'angular-datatables';
import { ToastrService } from 'ngx-toastr';
import { Subject } from 'rxjs';
import { Auth } from 'src/app/auth';
import Swal from 'sweetalert2';
@Component({
  selector: 'app-usuarios',
  templateUrl: './usuarios.component.html',
  styleUrls: ['./usuarios.component.css']
})
export class UsuariosComponent implements OnDestroy, OnInit {
  
  @ViewChild(DataTableDirective, { static: false })
  dtElement!: DataTableDirective;

  dtOptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject<any>();
  usuarios: any[] = [];
  roles: any;
  imagenactual:string;
  
  UsuarioForm: FormGroup = new FormGroup({
    IdPersona: new FormControl(),
    IdUsuario: new FormControl(),
    Nombres : new FormControl('',[Validators.required,Validators.minLength(3), Validators.maxLength(35)]),
    Apellidos : new FormControl('',[Validators.required,Validators.minLength(3), Validators.maxLength(35)]),
    Cedula : new FormControl('',[Validators.required,Validators.minLength(7), Validators.maxLength(10)]),
    Telefono : new FormControl('',[Validators.required]),
    Ciudad : new FormControl('',[Validators.required,Validators.minLength(4), Validators.maxLength(8)]),
    Email : new FormControl('',[Validators.required,Validators.email]),
    Foto : new FormControl('',[Validators.required]),
    Login : new FormControl('',[Validators.required,Validators.minLength(3), Validators.maxLength(35)]),
    Clave : new FormControl('',[Validators.required,Validators.minLength(3), Validators.maxLength(60)]),
    IdRol : new FormControl('',[Validators.required])
  });
  
  

  url: string='http://localhost/cami/api/usuarios';
  constructor(  private toastr: ToastrService,  private httpClient: HttpClient, private fb: FormBuilder) { }

  ngOnInit(): void {
    this.verform(false);
    this.dtOptions = {
      pagingType: 'full_numbers',
      pageLength: 5,
      responsive: true,

      destroy: true,
      info: true,
      language: {
        url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'
      },
    };
    this.listrol();
  }

  
  


  

  async ngAfterViewInit() {
    await this.listar();
    this.dtTrigger.next(0);
  }


  async listar(){
    const data = (await this.httpClient.get(this.url, Auth.autorizar()).toPromise()) as any;

     this.usuarios = data;
  }
  
  listrol(){
    this.httpClient.get("http://localhost/cami/api/roles", Auth.autorizar())
      .subscribe(lista => { 
        this.roles=lista;
      });
  }
    
   rerender() {
    this.dtElement.dtInstance.then(async (dtInstance: DataTables.Api) => {
      dtInstance.destroy();
      await this.listar();
      this.dtTrigger.next(0);
    });
   }
  refrescar() {
    this.rerender();
  }

  ngOnDestroy(): void {
    this.dtTrigger.unsubscribe();
  }

  verform(flag: boolean) {
    if (flag) {
      $("#form").show();
      $("#lista").hide();
    }
    else { 
      $("#form").hide();
      $("#lista").show();
    }

  }

  guardar() {
    console.log(this.UsuarioForm.value);
    if (this.UsuarioForm.valid) {
      const idusuario = this.UsuarioForm.get('IdPersona')?.value;
  
      if (idusuario == null) {
        this.httpClient.post('http://localhost/cami/api/personas/create', this.UsuarioForm.value, Auth.autorizar()).subscribe(result => {
       
          this.toastr.success('El usuario fue creado exitosamente', 'Usuario Creado');
          this.refrescar();
          this.verform(false);

        }, error => {
         this.verform(false);
        });
      }
     else {
        
        this.httpClient.put(`http://localhost/cami/api/personas/updateadm/${idusuario}`, this.UsuarioForm.value, Auth.autorizar()).subscribe(r => {    
          this.toastr.success('El usuario fue editado exitosamente', 'Usuario Editado');
          this.verform(false);
          this.refrescar();
        }, error => {
          console.log(error);
        });
      }



    }
  }

  editar(id: any) { 
    this.httpClient.get(`http://localhost/cami/api/usuarios/editperfil/${id}`, Auth.autorizar()).subscribe((data: any)=>{
      this.verform(true);
      this.UsuarioForm.controls['Nombres'].setValue(data[0].Nombres);
      this.UsuarioForm.controls['Apellidos'].setValue(data[0].Apellidos);
      this.UsuarioForm.controls['Login'].setValue(data[0].Login);
      this.UsuarioForm.controls['Clave'].setValue(data[0].Clave);
      this.UsuarioForm.controls['IdRol'].setValue(data[0].IdRol);
      this.UsuarioForm.controls['Ciudad'].setValue(data[0].Ciudad);
      this.UsuarioForm.controls['Cedula'].setValue(data[0].Cedula);
      this.UsuarioForm.controls['Telefono'].setValue(data[0].Telefono);
      this.UsuarioForm.controls['Email'].setValue(data[0].Email);
      this.UsuarioForm.controls['IdUsuario'].setValue(data[0].IdUsuario);
      this.UsuarioForm.controls['IdPersona'].setValue(data[0].IdPersona);
      this.UsuarioForm.controls['Foto'].setValue(data[0].Foto);
      this.imagenactual = "http://localhost/cami/" + data[0].Foto;
    });
  }



  habilitar(id:any){
    const body= {title: 'Desactivar'}
    this.httpClient.put<any>(`http://localhost/cami/api/usuarios/activar/${id}`, body , Auth.autorizar()).subscribe(d =>{
      this.toastr.success('Servicio Activado');
      this.refrescar();

    }, error =>{
     
    } );
  }

  deshabilitar(id:any){
    const body= {title: 'Desactivar'}
    this.httpClient.put<any>(`http://localhost/cami/api/usuarios/desactivar/${id}`, body, Auth.autorizar()).subscribe(d => {
      this.toastr.success('Servicio Activado');
      this.refrescar();
    }, error =>{
    
    } );
  }


  guardarfoto(evento: any) {
    const file = evento.target.files[0];
    var request = new FormData();
    const login = this.UsuarioForm.get('Login')?.value;
    request.append('foto', file);
    request.append('Login', login);
    this.httpClient.post('http://localhost/cami/api/usuarios/upfile', request, Auth.autorizarimg()).subscribe((data: any)=> { 
      this.UsuarioForm.controls['Foto'].setValue(data.result);
      this.imagenactual = "http://localhost/cami/" + data.result;

    } );
  }


}

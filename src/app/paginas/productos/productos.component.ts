import { HttpClient } from '@angular/common/http';
import { Component, OnInit, ViewChild } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { DataTableDirective } from 'angular-datatables';
import { ToastrService } from 'ngx-toastr';
import { Subject } from 'rxjs';
import { Auth } from 'src/app/auth';
import Swal from 'sweetalert2'; 

@Component({
  selector: 'app-productos',
  templateUrl: './productos.component.html',
  styleUrls: ['./productos.component.css']
})
export class ProductosComponent implements OnInit {

  @ViewChild(DataTableDirective, { static: false })
  dtElement!: DataTableDirective;

  dtOptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject<any>();
  imagenactual:string;
  nombre: string;
  productos: any[] = [];
  categorias: any;
  marcas:any;
  url: string='http://localhost/cami/api/productos/listaproductos';
  productoForm: FormGroup;


  constructor(private httpClient: HttpClient,  private toastr: ToastrService,  private fb: FormBuilder) {
    this.crearformulario();
  }


  ngOnInit(): void { 
    this.listarcategorias();
    this.listarmarcas();
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
      order:[4,'desc'],
    };
  }

  async ngAfterViewInit() {
    await this.listar();
    this.dtTrigger.next(0);
  }


  listarcategorias()
  {
    this.httpClient.get("http://localhost/cami/api/categorias", Auth.autorizar()).subscribe(lista => {
        this.categorias=lista;
      });
  }

  listarmarcas()
  {
    this.httpClient.get("http://localhost/cami/api/marcas", Auth.autorizar()).subscribe(lista => {
        this.marcas=lista;
      });
  }

  async listar(){
    const data = (await this.httpClient.get(this.url, Auth.autorizar()).toPromise()) as any;
     this.productos = data;
  }

  rerender() {
    this.dtElement.dtInstance.then(async (dtInstance: DataTables.Api) => {
      dtInstance.destroy();
      await this.listar();
      this.dtTrigger.next(0);
    });
  }
  reload() {
    this.rerender();
  }

  crearformulario(){
    this.productoForm = this.fb.group({
      IdProducto: new FormControl(),
      Nombre: new FormControl('', [Validators.required, Validators.minLength(3), Validators.maxLength(50)]),
      IdCategoria: new FormControl(),
      IdMarca: new FormControl(),
      Precio: new FormControl('', [Validators.required, Validators.min(10), Validators.max(999)]),
      Stock: new FormControl('', [Validators.required, Validators.min(10), Validators.max(999)]),
      Descripcion : new FormControl('',[Validators.required,Validators.minLength(3), Validators.maxLength(500)]),
      Imagen : new FormControl('',[Validators.required])
    });
  }

  verform(bandera:boolean){
    if (bandera) {
      this.productoForm.reset();
     $('#form').show();
     $('#lista').hide();
     $('#btnnuevo').hide();
     
    }
    else{
     $('#form').hide();
     $('#lista').show();
     $('#btnnuevo').show();
    }
  }
  

  guardar() {
    console.log(this.productoForm.value);
    if(this.productoForm.valid){
      const idproducto = this.productoForm.get('IdProducto')?.value;

      if ( idproducto== null) {
    
        this.httpClient.post('http://localhost/cami/api/productos/create' , this.productoForm.value, Auth.autorizar()).subscribe(result => {
          this.toastr.success('La producto fue creada exitosamente', 'producto creada', {
            timeOut: 3000,
            positionClass: 'toast-bottom-right'
          });
          this.reload();
        this.verform(false);
      }, error => {
          this.verform(false);
        
        });
      }
        else{
  
          this.httpClient.put(`http://localhost/cami/api/productos/update/${idproducto}`, this.productoForm.value, Auth.autorizar()).subscribe(r=>{
            this.reload();
            this.toastr.success('La producto fue editada exitosamente', 'producto editada', {
              timeOut: 3000,
              positionClass: 'toast-bottom-right'
            });
            this.verform(false); 
         }, error=>{
          
          });
        } 
    }
  }

  activar(idproducto:any){
    const body = { title: 'Activar' }
 
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-success',
            
          cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
      });
      
    swalWithBootstrapButtons.fire({
      title: 'Habilitar producto',
      text: "¿Quieres habilitar esta producto?",
      icon: 'warning',
      background: '#fff url(assets/img/trees.png)',
      backdrop: ` 
        rgba(4, 42, 6, 0.482)
        url("assets/img/hojas2.gif")
        left top
      
      `,
      showCancelButton: true,
      confirmButtonColor: '#2CA41A',
      confirmButtonText: 'Si, habilitar',
      cancelButtonText: 'No, cancelar',
      cancelButtonColor: '#C21616',
    }).then((result) => {
      if (result.isConfirmed) {

        this.httpClient.put<any>(`http://localhost/cami/api/productos/activar/${idproducto}`, body, Auth.autorizar()).subscribe(d => {
          this.reload();  
         });
        swalWithBootstrapButtons.fire(
          'Habilitado',
          'Esta producto fue habilitada exitosamente',
          'success'
        )
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        swalWithBootstrapButtons.fire(
          'Cancelado',
          'La producto no fue habilitada',
          'error'
        )
      }
    });
  
  }


  desactivar(idproducto: any) {
    
    const body= {title: 'Desactivar'}
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-success',
          
        cancelButton: 'btn btn-danger'
      },
      buttonsStyling: true
    });
    
  swalWithBootstrapButtons.fire({
    title: 'Deshabilitar producto',
    text: "¿Quieres deshabilitar esta producto?",
    icon: 'warning',
    background: '#fff url(assets/img/trees.png)',
    backdrop: ` 
      rgba(4, 42, 6, 0.482)
      url("assets/img/hojas2.gif")
      left top
    
    `,
    showCancelButton: true,
    confirmButtonColor: '#2CA41A',
    confirmButtonText: 'Si, Deshabilitar',
    cancelButtonText: 'No, cancelar',
    cancelButtonColor: '#C21616',
  }).then((result) => {
    if (result.isConfirmed) {

      this.httpClient.put<any>(`http://localhost/cami/api/productos/desactivar/${idproducto}`, body, Auth.autorizar()).subscribe(d => {
        this.reload();  
       });
      swalWithBootstrapButtons.fire(
        'Deshabilitado',
        'Esta producto fue deshabilitada exitosamente',
        'success'
      )
    } else if (
      /* Read more about handling dismissals below */
      result.dismiss === Swal.DismissReason.cancel
    ) {
      swalWithBootstrapButtons.fire(
        'Cancelado',
        'La producto no fue deshabilitada',
        'error'
      )
    }
  });
    
  }

  editar(idproducto: any) {
    console.log(idproducto);
    this.httpClient.get(`http://localhost/cami/api/productos/edit/${idproducto}`, Auth.autorizar()).subscribe((data: any)=>{
      this.verform(true);
      this.productoForm.controls['IdProducto'].setValue(idproducto);
      this.productoForm.controls['Nombre'].setValue(data.Nombre);
      this.productoForm.controls['IdCategoria'].setValue(data.IdCategoria);
      this.productoForm.controls['IdMarca'].setValue(data.IdMarca);
      this.productoForm.controls['Precio'].setValue(data.Precio);
      this.productoForm.controls['Stock'].setValue(data.Stock);
      this.productoForm.controls['Stock'].setValue(data.Stock);
      this.productoForm.controls['Descripcion'].setValue(data.Descripcion);
      this.productoForm.controls['Imagen'].setValue(data.Imagen);

    });
  }
  

  guardarfoto(evento: any) {
    const file = evento.target.files[0];
    var request = new FormData();
    const login = this.productoForm.get('Nombre')?.value;
    request.append('foto', file);
    request.append('Login', login);
    this.httpClient.post('http://localhost/cami/api/productos/upfile', request, Auth.autorizarimg()).subscribe((data: any)=> { 
      this.productoForm.controls['Imagen'].setValue(data.result);
      this.imagenactual = "http://localhost/cami/" + data.result;

    } );
  }

ngOnDestroy(): void {
  this.dtTrigger.unsubscribe();
}

  
}

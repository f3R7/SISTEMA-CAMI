import { HttpClient } from '@angular/common/http';
import { Component, OnInit, ViewChild } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { DataTableDirective } from 'angular-datatables';
import { ToastrService } from 'ngx-toastr';
import { Subject } from 'rxjs';
import { Auth } from 'src/app/auth';
import Swal from 'sweetalert2'; 

@Component({
  selector: 'app-categorias',
  templateUrl: './categorias.component.html',
  styleUrls: ['./categorias.component.css']
})
export class CategoriasComponent implements OnInit {

  @ViewChild(DataTableDirective, { static: false })
  dtElement!: DataTableDirective;

  dtOptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject<any>();
  imagenactual:string;
  nombre: string;
  categorias:any[]=[];
  url: string='http://localhost/cami/api/categorias';
  categoriaForm: FormGroup;


  constructor(private httpClient: HttpClient,  private toastr: ToastrService,  private fb: FormBuilder) {
    this.crearformulario();
  }


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
      order:[1,'desc'],
    };
  }

  async ngAfterViewInit() {
    await this.listar();
    this.dtTrigger.next(0);
  }

  async listar(){
    const data = (await this.httpClient.get(this.url, Auth.autorizar()).toPromise()) as any;
     this.categorias = data;
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
    this.categoriaForm = this.fb.group({
      Idcategoria: new FormControl(),
      Nombre : new FormControl('',[Validators.required, Validators.minLength(3), Validators.maxLength(50)]),
    });
  }

  verform(bandera:boolean){
    if (bandera) {
      this.categoriaForm.reset();
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
  

  guardar(){
    if(this.categoriaForm.valid){
      const idcategoria = this.categoriaForm.get('Idcategoria')?.value;

      if ( idcategoria== null) {
    
        this.httpClient.post('http://localhost/cami/api/categorias/create' , this.categoriaForm.value, Auth.autorizar()).subscribe(result => {
          this.toastr.success('La categoria fue creada exitosamente', 'categoria creada', {
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
  
          this.httpClient.put(`http://localhost/cami/api/categorias/update/${idcategoria}`, this.categoriaForm.value, Auth.autorizar()).subscribe(r=>{
            this.reload();
            this.toastr.success('La categoria fue editada exitosamente', 'categoria editada', {
              timeOut: 3000,
              positionClass: 'toast-bottom-right'
            });
            this.verform(false); 
         }, error=>{
          
          });
        } 
    }
  }

  activar(idcategoria:any){
    const body = { title: 'Activar' }
 
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-success',
            
          cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
      });
      
    swalWithBootstrapButtons.fire({
      title: 'Habilitar categoria',
      text: "¿Quieres habilitar esta categoria?",
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

        this.httpClient.put<any>(`http://localhost/cami/api/categorias/activar/${idcategoria}`, body, Auth.autorizar()).subscribe(d => {
          this.reload();  
         });
        swalWithBootstrapButtons.fire(
          'Habilitado',
          'Esta categoria fue habilitada exitosamente',
          'success'
        )
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        swalWithBootstrapButtons.fire(
          'Cancelado',
          'La categoria no fue habilitada',
          'error'
        )
      }
    });
  
  }


  desactivar(idcategoria: any) {
    
    const body= {title: 'Desactivar'}
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-success',
          
        cancelButton: 'btn btn-danger'
      },
      buttonsStyling: true
    });
    
  swalWithBootstrapButtons.fire({
    title: 'Deshabilitar categoria',
    text: "¿Quieres deshabilitar esta categoria?",
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

      this.httpClient.put<any>(`http://localhost/cami/api/categorias/desactivar/${idcategoria}`, body, Auth.autorizar()).subscribe(d => {
        this.reload();  
       });
      swalWithBootstrapButtons.fire(
        'Deshabilitado',
        'Esta categoria fue deshabilitada exitosamente',
        'success'
      )
    } else if (
      /* Read more about handling dismissals below */
      result.dismiss === Swal.DismissReason.cancel
    ) {
      swalWithBootstrapButtons.fire(
        'Cancelado',
        'La categoria no fue deshabilitada',
        'error'
      )
    }
  });
    
  }

  editar(idcategoria:any){
    this.httpClient.get(`http://localhost/cami/api/categorias/edit/${idcategoria}`, Auth.autorizar()).subscribe((data: any)=>{
      this.verform(true);
      this.categoriaForm.controls['Idcategoria'].setValue(idcategoria);
      this.categoriaForm.controls['Nombre'].setValue(data.Nombre);
      this.categoriaForm.controls['Pais'].setValue(data.Pais);
      this.categoriaForm.controls['Ciudad'].setValue(data.Ciudad);
    
    });
}

ngOnDestroy(): void {
  this.dtTrigger.unsubscribe();
}

  
}

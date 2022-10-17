import { HttpClient } from '@angular/common/http';
import { Component, OnDestroy, OnInit } from '@angular/core';
import { Subject } from 'rxjs';
import { Auth } from 'src/app/auth';



@Component({
  selector: 'app-roles',
  templateUrl: './roles.component.html',
  styleUrls: ['./roles.component.css']
})
export class RolesComponent implements OnDestroy, OnInit {
  url: string = 'http://localhost/cami/api/roles'  ;
  dtOptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject<any>();
  roles: any[] = [];
  
  constructor(private httpClient: HttpClient) { }
  
  ngOnInit(): void {
    this.dtOptions = {
      pagingType: 'full_numbers',
      pageLength: 5,
      language: {
        url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'
      }
    };
    this.httpClient.get<any[]>(this.url, Auth.autorizar())
      .subscribe(data => {
        this.roles = data;
        this.dtTrigger.next(0);
      });
  }

  ngOnDestroy(): void {
    this.dtTrigger.unsubscribe();
  }

  
}

using Microsoft.AspNetCore.Mvc;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    public abstract class BaseReaderController<TService,TModel> : Controller
        where TModel : BaseModel, new()
        where TService : BaseCrudService<TModel>
    {
        protected readonly TService Service;

        protected BaseReaderController(TService service)
        {
            Service = service;
        }

        [HttpGet]
        [Route("{id:int}")]
        [ProducesResponseType(401)]
        [ProducesResponseType(200)]
        public virtual ActionResult Get(int id)
        {
            var entity = Service.Get(id);
            return Ok(entity);
        }

        [HttpGet]
        [ProducesResponseType(401)]
        [ProducesResponseType(200)]
        public virtual ActionResult Get()
        {
            var result = Service.GetList();
            return Ok(result);
        }
    }
}

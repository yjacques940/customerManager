using Microsoft.AspNetCore.Mvc;
using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    [Produces("application/json")]
    public abstract class BaseController<TService, TModel> : Controller
        where TModel : BaseModel, new()
        where TService : BaseCrudService<TModel>
    {
        protected readonly TService Service;

        protected BaseController(TService service)
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

        [HttpPost]
        [ProducesResponseType(401)]
        [ProducesResponseType(200)]
        public virtual ActionResult Post([FromBody] TModel entity)
        {
            if (Service.AddOrUpdate(entity) != -1)
                return Ok(new { id = entity.Id });

            return BadRequest();
        }

        [HttpDelete]
        [Route("{id:int}")]
        [ProducesResponseType(401)]
        [ProducesResponseType(200)]
        public virtual ActionResult Delete(int id)
        {
            if (Service.Remove(id))
                return NoContent();

            return BadRequest();
        }
    }
}

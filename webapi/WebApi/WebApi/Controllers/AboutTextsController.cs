using Microsoft.AspNetCore.Mvc;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class AboutTextsController : BaseController<AboutTextService, AboutText>
    {
        public AboutTextsController(AboutTextService service) : base(service)
        {
        }

        [HttpGet, Route("GetActiveText")]
        public ActionResult GetActiveText()
        {
            return Ok(Service.GetActiveText());
        }

        [HttpPost, Route("AddNewText")]
        public ActionResult AddNewText([FromBody] AboutText aboutText)
        {
            if(aboutText != null)
                return Ok(Service.AddNewText(aboutText));

            return BadRequest();
        }
        
        [HttpGet, Route("GetAboutTextById/{id}")]
        public ActionResult GetAboutTextById(int id)
        {
            return Ok(Service.GetAboutTextById(id));
        }
    }
}

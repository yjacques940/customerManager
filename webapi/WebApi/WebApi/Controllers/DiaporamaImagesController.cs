using Microsoft.AspNetCore.Mvc;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.DTO;
using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class DiaporamaImagesController : BaseController<DiaporamaImageService, DiaporamaImage>
    {
        public DiaporamaImagesController(DiaporamaImageService service) : base(service)
        {
        }

        [HttpPost, Route("AddNewImage")]
        public ActionResult AddNewImage([FromBody] DiaporamaImage diaporamaImage)
        {
            if (diaporamaImage != null)
                return Ok(Service.AddNewImage(diaporamaImage));
            return NoContent();
        }

        [HttpGet, Route("GetAllImages")]
        public ActionResult GetAllImages()
        {
            return Ok(Service.GetAllImages());
        }

        [HttpGet, Route("GetAllDisplayedImages")]
        public ActionResult GetAllDisplayedImages()
        {
            return Ok(Service.GetAllDisplayedImages());
        }

        [HttpPost, Route("UpdateDisplayAndOrder")]
        public ActionResult UpdateDisplayAndOrder([FromBody]DiaporamaImagesInformation diaporamaImagesInformation)
        {
            if (diaporamaImagesInformation != null)
                return Ok(Service.UpdateDisplayAndOrder(diaporamaImagesInformation));

            return NoContent();
        }
    }
}

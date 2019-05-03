using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.Data;
using WebApi.Models;

namespace WebApi.Services
{
    public class DiaporamaImageService : BaseCrudService<DiaporamaImage>
    {
        public DiaporamaImageService(IWebApiContext context) : base(context)
        {
        }
    }
}

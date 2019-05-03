using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace WebApi.Models
{
    public class DiaporamaImage : BaseModel
    {
        public bool IsDisplayed { get; set; }
        public int Order { get; set; }
        public string Path { get; set; }
    }
}

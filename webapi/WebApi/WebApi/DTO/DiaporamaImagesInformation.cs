using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace WebApi.DTO
{
    public class DiaporamaImagesInformation
    {
        public List<ImageDisplayAndOrderInformation> ImageDisplayAndOrderInformation { get; set; }
        public List<int> IdsToDelete { get; set; }
    }
}

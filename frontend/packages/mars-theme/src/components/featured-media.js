import { connect, styled } from "frontity";
import Image from "@frontity/components/image";

const FeaturedMedia = ({ state, id, className }) => {
  const media = state.source.attachment[id];

  if (!media) return null;

  const srcset =
    Object.values(media.media_details.sizes)
      // Get the url and width of each size.
      .map((item) => [item.source_url, item.width])
      // Recude them to a string with the format required by `srcset`.
      .reduce(
        (final, current, index, array) =>
          final.concat(
            `${current.join(" ")}w${index !== array.length - 1 ? ", " : ""}`
          ),
        ""
      ) || null;

  return (
    <>
      <StyledImage
        alt={media.title.rendered}
        src={media.source_url}
        srcSet={srcset}
        className={className}
      />
    </>
  );
};

export default connect(FeaturedMedia);

const Container = styled.div``;

const StyledImage = styled(Image)``;
